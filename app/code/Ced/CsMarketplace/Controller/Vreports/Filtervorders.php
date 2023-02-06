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
 * @package     Ced_CsMarketplace
 * @author      CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (https://cedcommerce.com/)
 * @license     https://cedcommerce.com/license-agreement.txt
 */

namespace Ced\CsMarketplace\Controller\Vreports;

use Ced\CsMarketplace\Model\Session as MarketplaceSession;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\UrlFactory;

/**
 * Class Filtervorders
 * @package Ced\CsMarketplace\Controller\Vreports
 */
class Filtervorders extends \Ced\CsMarketplace\Controller\Vendor
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    public $resultJsonFactory;

    /**
     * @var MarketplaceSession
     */
    public $marketplaceSession;

    /**
     * @var \Zend\Uri\Uri
     */
    protected $zendUri;

    /**
     * Filtervorders constructor.
     * @param Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param Session $customerSession
     * @param UrlFactory $urlFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
     * @param \Ced\CsMarketplace\Helper\Data $csmarketplaceHelper
     * @param \Ced\CsMarketplace\Helper\Acl $aclHelper
     * @param \Ced\CsMarketplace\Model\VendorFactory $vendor
     * @param MarketplaceSession $MarketplaceSession
     * @param \Zend\Uri\Uri $zendUri
     */
    public function __construct(
        Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        Session $customerSession,
        UrlFactory $urlFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        \Ced\CsMarketplace\Helper\Data $csmarketplaceHelper,
        \Ced\CsMarketplace\Helper\Acl $aclHelper,
        \Ced\CsMarketplace\Model\VendorFactory $vendor,
        MarketplaceSession $MarketplaceSession,
        \Zend\Uri\Uri $zendUri
    )
    {
        $this->resultJsonFactory = $jsonFactory;
        $this->marketplaceSession = $MarketplaceSession;
        $this->zendUri = $zendUri;
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
    }

    /**
     * @return bool|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();

        $params1 = $this->getRequest()->getParams();
        $params1 = current($params1);

        $this->zendUri->setQuery($params1);
        $params = $this->zendUri->getQueryAsArray();

        if (!isset($params['p']) && !isset($params['limit']) && is_array($params)) {
            $this->marketplaceSession->setData('vorders_reports_filter', $params);
        }
        $result = $this->resultPageFactory->create(true)->getLayout()
            ->createBlock('Ced\CsMarketplace\Block\Vreports\Vorders\ListOrders')
            ->setName('csmarketplace_report_orders3')
            ->setTemplate('Ced_CsMarketplace::vreports/vorders/list.phtml')->toHtml();

        return $resultJson->setData($result);
    }
}
