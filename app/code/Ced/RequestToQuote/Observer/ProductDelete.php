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
 * @package     Ced_RequestToQuote
 * @author      CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (https://cedcommerce.com/)
 * @license      https://cedcommerce.com/license-agreement.txt
 */

namespace Ced\RequestToQuote\Observer;

use Magento\Framework\Event\ObserverInterface;
use Ced\RequestToQuote\Model\ResourceModel\RequestQuote\CollectionFactory;

/**
 * Class Test
 * @package Ced\RequestToQuote\Observer
 */
class ProductDelete implements ObserverInterface
{
    /**
     * @var CollectionFactory
     */
    protected $requestQuoteCollectionFactory;

    /**
     * CustomerDelete constructor.
     * @param CollectionFactory $poCollectionFactory
     */
    public function __construct(
        CollectionFactory $poCollectionFactory
    ) {
        $this->requestQuoteCollectionFactory = $poCollectionFactory;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer) {
        $product = $observer->getEvent()->getProduct();
        $requestQuote = $this->requestQuoteCollectionFactory->create()
            ->addFieldToFilter('product_id', $product->getId());
        if ($requestQuote) {
            $requestQuote->walk('delete');
        }
    }
}