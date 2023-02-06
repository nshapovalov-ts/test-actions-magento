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
 * @package     Ced_CsVendorReview
 * @author      CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (https://cedcommerce.com/)
 * @license     https://cedcommerce.com/license-agreement.txt
 */

namespace Ced\CsVendorReview\Block\Rating;

class Lists extends \Magento\Framework\View\Element\Template
{
    protected $_vendor;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    public $messageManager;

    /**
     * @var \Ced\CsVendorReview\Model\ResourceModel\Review\CollectionFactory
     */
    protected $reviewCollection;

    /**
     * @var \Ced\CsVendorReview\Model\ResourceModel\Rating\CollectionFactory
     */
    protected $ratingCollection;

    /**
     * @var \Ced\CsVendorReview\Helper\Data
     */
    public $csVendorReviewHelper;

    /**
     * Lists constructor.
     * @param \Magento\Framework\Registry $registry
     * @param \Ced\CsVendorReview\Model\ResourceModel\Review\CollectionFactory $reviewCollectionFactory
     * @param \Ced\CsVendorReview\Model\ResourceModel\Rating\CollectionFactory $ratingCollectionFactory
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Ced\CsVendorReview\Helper\Data $csVendorReviewHelper
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        \Ced\CsVendorReview\Model\ResourceModel\Review\CollectionFactory $reviewCollectionFactory,
        \Ced\CsVendorReview\Model\ResourceModel\Rating\CollectionFactory $ratingCollectionFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Ced\CsVendorReview\Helper\Data $csVendorReviewHelper,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->reviewCollection = $reviewCollectionFactory;
        $this->ratingCollection = $ratingCollectionFactory;
        $this->messageManager = $messageManager;
        $this->csVendorReviewHelper = $csVendorReviewHelper;
        $reviews = $this->reviewCollection->create()
            ->addFieldToFilter('vendor_id', $this->getVendorId())
            ->addFieldToFilter('status', 1)
            ->setOrder('created_at', 'desc');
        $this->setReviews($reviews);
    }

    /**
     * @return $this|Lists
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $toolbar = $this->getLayout()->getBlock('product_review_list.toolbar');
        if ($toolbar) {
            $toolbar->setCollection($this->getReviews());
            $this->setChild('toolbar', $toolbar);
            $this->getReviews()->load();
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRatings()
    {
        return $this->ratingCollection->create()
            ->setOrder('sort_order', 'asc');
    }

    /**
     * @return mixed
     */
    public function getVendor()
    {
        if (!$this->_vendor) {
            $this->_vendor = $this->registry->registry('current_vendor');
        }
        return $this->_vendor;
    }

    /**
     * @return mixed
     */
    public function getVendorId()
    {
        return $this->getVendor()->getId();
    }

    /**
     * @return mixed
     */
    public function isAllowed()
    {
        return $this->csVendorReviewHelper->isCustomerAllowed();
    }

    /**
     * @return bool
     */
    public function checkVendorProducts()
    {
        return $this->csVendorReviewHelper->checkVendorProduct();
    }

    /**
     * @return bool
     */
    public function isUserLoggedIn()
    {
        return $this->csVendorReviewHelper->isUserLoggedIn();
    }

    /**
     * @return \Magento\Framework\Message\Collection
     */
    public function getMessages()
    {
        return $this->messageManager->getMessages(true, "message_manager_example");
    }
}
