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

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;
use Ced\RequestToQuote\Model\QuoteFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Ced\CsMarketplace\Helper\Data as CsMarketplaceHelperData;
use Ced\CsMarketplace\Helper\Acl;
use Ced\CsMarketplace\Model\VendorFactory;
use Ced\CsRfq\Helper\Data as CsRfqHelperData;

class View extends \Ced\CsMarketplace\Controller\Vendor
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * @var QuoteFactory
     */
    protected $quoteFactory;

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
     * @param Registry $coreRegistry
     * @param QuoteFactory $quoteFactory
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
        Registry $coreRegistry,
        QuoteFactory $quoteFactory,
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
        $this->_coreRegistry = $coreRegistry;
        $this->quoteFactory = $quoteFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->csRfqHelper = $csRfqHelper;
    }

    /**
     * @return \Magento\Framework\View\Result\Page|void
     */
    public function execute()
    {
        if (! $this->csRfqHelper->isVendorRfqEnable()) {
            $this->_redirect('csmarketplace/vendor');
            return;
        }
        if ($id = $this->getRequest()->getParam('id')) {
            $currentQuote = $this->quoteFactory->create()->load($id);
            if ($currentQuote && $currentQuote->getId()) {
                $this->_coreRegistry->register('current_quote', $currentQuote);
                $resultPage = $this->resultPageFactory->create();
                $resultPage->getConfig()->getTitle()->prepend(__('Quote # %1', $currentQuote->getQuoteIncrementId()));
                return $resultPage;
            }
            $this->messageManager->addErrorMessage(__('This quote no longer exist.'));
        }
        return $this->_redirect('rfq/quotes/index');
    }
}
