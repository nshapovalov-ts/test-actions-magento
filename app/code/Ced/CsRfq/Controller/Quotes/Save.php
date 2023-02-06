<?php

/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://cedcommerce.com/license-agreement.txt
 *
 * @category    Ced
 * @package     Ced_CsRfq
 * @author      CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (https://cedcommerce.com/)
 * @license      https://cedcommerce.com/license-agreement.txt
 */

namespace Ced\CsRfq\Controller\Quotes;

use Ced\RequestToQuote\Model\Quote;
use Magento\Framework\App\Action\Context;
use Ced\RequestToQuote\Model\MessageFactory;
use Ced\RequestToQuote\Model\ResourceModel\QuoteDetail\CollectionFactory;
use Ced\RequestToQuote\Helper\Data;
use Ced\RequestToQuote\Model\Source\QuoteStatus;
use Magento\Customer\Model\Session;
use Magento\Framework\UrlFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;
use Magento\Framework\Controller\Result\JsonFactory;
use Ced\CsMarketplace\Helper\Data as CsMarketplaceHelperData;
use Ced\CsMarketplace\Helper\Acl;
use Ced\CsMarketplace\Model\VendorFactory;
use Ced\CsRfq\Helper\Data as CsRfqHelperData;

class Save extends \Ced\CsMarketplace\Controller\Vendor
{
    /**
     * @var \Magento\Catalog\Model\Product\AttributeSet\BuildFactory
     */
    protected $buildFactory;

    /**
     * @var \Magento\Framework\Filter\FilterManager
     */
    protected $filterManager;

    /**
     * @var \Magento\Catalog\Helper\Product
     */
    protected $productHelper;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Eav\AttributeFactory
     */
    protected $attributeFactory;

    /**
     * @var \Magento\Eav\Model\Adminhtml\System\Config\Source\Inputtype\ValidatorFactory
     */
    protected $validatorFactory;

    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory
     */
    protected $groupCollectionFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Session $customerSession
     * @param UrlFactory $urlFactory
     * @param Registry $registry
     * @param JsonFactory $jsonFactory
     * @param CsMarketplaceHelperData $csmarketplaceHelper
     * @param Acl $aclHelper
     * @param VendorFactory $vendor
     * @param MessageFactory $message
     * @param CollectionFactory $quotedetail
     * @param Quote $quote
     * @param Data $helper
     * @param QuoteStatus $quoteStatus
     * @param CsRfqHelperData $csRfqHelper
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Session $customerSession,
        UrlFactory $urlFactory,
        Registry $registry,
        JsonFactory $jsonFactory,
        CsMarketplaceHelperData $csmarketplaceHelper,
        Acl $aclHelper,
        VendorFactory $vendor,
        MessageFactory $message,
        CollectionFactory $quotedetail,
        Quote $quote,
        Data $helper,
        QuoteStatus $quoteStatus,
        CsRfqHelperData $csRfqHelper
    ) {
    	parent::__construct(
            $context,
            $resultPageFactory,
            $customerSession,
            $urlFactory,
            $registry,
            $jsonFactory,
            $csmarketplaceHelper,
            $aclHelper,
            $vendor
        );
    	$this->message = $message;
        $this->quotedetail = $quotedetail;
        $this->quote = $quote;
        $this->helper = $helper;
        $this->quoteStatus = $quoteStatus;
        $this->csRfqHelper = $csRfqHelper;
    }

    /**
     * @return void
     */
    public function execute()
    {
        if ($this->helper->isEnable() == '0') {
            $this->messageManager->addErrorMessage(__('Please Enable the extension.'));
            return $this->_redirect('*/*/');
        }

        if (! $this->csRfqHelper->isVendorRfqEnable()) {
            $this->_redirect('csmarketplace/vendor');
            return;
        }
        
        $data = $this->getRequest()->getPostValue();
        $quote_id = $data['id'];
        $quote = $this->quote->load($quote_id);
        $vendor = $this->_getSession()->getVendorId();
        try {
        	
            $item_info = [];
            
            $totals = [];
            
            $quoteDescription = $this->quotedetail->create()->addFieldToFilter('quote_id', $quote_id);

            $customerId = $quote->getCustomerId();
            
            $quote_total_qty = 0;
            
            $quote_total_price = 0;
            
            $updateQtyPriceFlag = false;
            
            foreach ($quoteDescription as $value) {
                $product_id = $value->getProductId();
                
                if (isset($data['item'])) {
                    
                	if ($data['item'][$value->getId()]['unitprice'] > 0) {
                        $updateduprice = $data['item'][$value->getId()]['unitprice'];
                    } elseif ($data['item'][$value->getId()]['unitprice'] == 0 &&
                              $data['status'] == \Ced\RequestToQuote\Model\Quote::QUOTE_STATUS_APPROVED) {
                        $updateduprice = $value->getPrice();
                    } else {
                        $updateduprice = $value->getUnitPrice();
                    }
                    if ($data['item'][$value->getId()]['qty'] > 0) {
                        $updatedqty = $data['item'][$value->getId()]['qty'];
                    } elseif ($data['item'][$value->getId()]['qty'] == 0 &&
                              $data['status'] == \Ced\RequestToQuote\Model\Quote::QUOTE_STATUS_APPROVED) {
                        $updatedqty = $value->getProductQty();
                    } else {
                        $updatedqty = $value->getQuoteUpdatedQty();
                    }
                } else {
                    $updateduprice = $value->getUnitPrice();
                    $updatedqty = $value->getQuoteUpdatedQty();
                }
                if ($updateduprice != $value->getUnitPrice() || $updatedqty != $value->getQuoteUpdatedQty()) {
                    $updateQtyPriceFlag = true;
                }
                $quote_total_qty += $updatedqty;
                $updatedprice = ($updatedqty * $updateduprice);
                $quote_total_price += $updatedprice;
                $value->setData('quote_updated_qty', $updatedqty);
                $value->setData('updated_price', $updatedprice);
                $value->setData('unit_price', $updateduprice);
                $value->setData('last_updated_by', 'Vendor');
                $value->save();
                $item_info[$product_id]['prod_id'] = $product_id;
                $item_info[$product_id]['name'] = $value->getName();
                $item_info[$product_id]['sku'] = $value->getSku();
                $item_info[$product_id]['qty'] = $updatedqty;
                $item_info[$product_id]['price'] = $updatedprice;
            }           
            $quote->setQuoteUpdatedQty($quote_total_qty)
                ->setQuoteUpdatedPrice($quote_total_price);
            $this->quote->save();
            if ($updateQtyPriceFlag && $data['status'] == \Ced\RequestToQuote\Model\Quote::QUOTE_STATUS_PENDING) {
                $quote->setStatus(\Ced\RequestToQuote\Model\Quote::QUOTE_STATUS_PROCESSING);
            } else {
                $quote->setStatus($data['status']);
            }

            $quote->setData('last_updated_by','Vendor');
            
            if($quote->getStatus() == \Ced\RequestToQuote\Model\Quote::QUOTE_STATUS_APPROVED) {
                $quote->setData('remaining_qty', $quote->getQuoteUpdatedQty());
            }
            
            $quote->save();
            if(!empty($data['message'])){
                $quoteMessage = $this->message->create();
                $quoteMessage->setData('quote_id', $quote_id);
                $quoteMessage->setData('customer_id',$customerId);
                $quoteMessage->setData('vendor_id', $vendor);
                $quoteMessage->setData('message', $data['message']);
                $quoteMessage->setData('sent_by','Vendor');
                $quoteMessage->save();
            }
            $status = $quote->getStatus();
            $label = $this->quoteStatus->getOptionText($status);
            $email = $quote->getCustomerEmail();
            $totals['subtotal'] = $quote->getQuoteUpdatedPrice();
            $totals['shipping'] = $quote->getShippingAmount();
            $totals['grandtotal'] = $totals['subtotal'] + $totals['shipping'];
            $template = $this->helper->getConfigValue(Data::QUOTE_UPDATE_EMAIL);
            $template_variables = array('quote_id' => '#'.$quote->getQuoteIncrementId(),
                'quote_status' => $label,
                'item_info' => $item_info,
                'totals' => $totals);
            $this->helper->sendEmail($template,$email,$template_variables);
            $this->messageManager->addSuccessMessage(__('Quote # %1 has been successfully updated', $quote->getQuoteIncrementId()));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error Occured, Please try again.'));
        }
        return $this->_redirect('rfq/quotes/index');
    }
}

