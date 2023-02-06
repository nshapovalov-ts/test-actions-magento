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
 * @package     Ced_CsProduct
 * @author      CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (https://cedcommerce.com/)
 * @license      https://cedcommerce.com/license-agreement.txt
 */

namespace Ced\CsProduct\Controller\Wysiwyg;

use Magento\Framework\App\Action;

class Directive extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Url\DecoderInterface
     */
    protected $urlDecoder;

    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var \Magento\Cms\Model\Template\Filter
     */
    protected $filter;

    /**
     * @var \Magento\Framework\Image\AdapterFactory
     */
    protected $adapterFactory;

    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $config;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * Directive constructor.
     * @param Action\Context $context
     * @param \Magento\Framework\Url\DecoderInterface $urlDecoder
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Magento\Cms\Model\Template\Filter $filter
     * @param \Magento\Framework\Image\AdapterFactory $adapterFactory
     * @param \Magento\Cms\Model\Wysiwyg\Config $config
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\Url\DecoderInterface $urlDecoder,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Cms\Model\Template\Filter $filter,
        \Magento\Framework\Image\AdapterFactory $adapterFactory,
        \Magento\Cms\Model\Wysiwyg\Config $config,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->urlDecoder = $urlDecoder;
        $this->resultRawFactory = $resultRawFactory;
        $this->filter = $filter;
        $this->adapterFactory = $adapterFactory;
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * Template directives callback
     *
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        $directive = $this->getRequest()->getParam('___directive');
        $directive = $this->urlDecoder->decode($directive);
        $imagePath = $this->filter->filter($directive);

        /** @var \Magento\Framework\Image\Adapter\AdapterInterface $image */
        $image = $this->adapterFactory->create();

        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();

        try {
            $image->open($imagePath);
            $resultRaw->setHeader('Content-Type', $image->getMimeType());
            $resultRaw->setContents($image->getImage());
        } catch (\Exception $e) {
            $imagePath = $this->config->getSkinImagePlaceholderPath();
            $image->open($imagePath);
            $resultRaw->setHeader('Content-Type', $image->getMimeType());
            $resultRaw->setContents($image->getImage());
            $this->logger->critical($e);
        }
        return $resultRaw;
    }
}
