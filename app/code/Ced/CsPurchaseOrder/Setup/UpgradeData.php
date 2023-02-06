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
 * @package     Ced_CsPurchaseOrder
 * @author      CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (https://cedcommerce.com/)
 * @license     https://cedcommerce.com/license-agreement.txt
 */

namespace Ced\CsPurchaseOrder\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Class UpgradeData
 * @package Ced\CsPurchaseOrder\Setup
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * UpgradeData constructor.
     * @param \Magento\Cms\Model\BlockFactory $blockFactory
     */
    public function __construct(
        \Magento\Cms\Model\BlockFactory $blockFactory
    )
    {
        $this->blockFactory = $blockFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Exception
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if ($context->getVersion()
            && version_compare($context->getVersion(), '0.0.4','<')
        ) {

            /*START : CMS block for TOC sections*/
            $featuresBlock = $this->blockFactory->create();
            $featuresBlock->load('ced-category-customer-toc', 'identifier');
            if (!$featuresBlock->getId()) {
                $features = [
                    'title' => 'Customer TOC for Category',
                    'identifier' => 'ced-category-customer-toc',
                    'content' => '<p style="text-align: center;"> <strong>THIS AGREEMENT WITNESSES AS UNDER</strong></p>
                                   <p style="text-align: center;"> Terms and Conditions </p>',
                    'stores' => 0,
                    'is_active' => 1,
                ];
                $this->blockFactory->create()->setData($features)->save();
            }
            /*END : CMS block for TOC sections*/
            //phpcs:enable
        }

        $setup->endSetup();
    }
}
