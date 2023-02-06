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
 * @author        CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (https://cedcommerce.com/)
 * @license      https://cedcommerce.com/license-agreement.txt
 */

namespace Ced\CsPurchaseOrder\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class UpgradeSchema
 * @package Ced\CsPurchaseOrder\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $itemtableName = $installer->getTable('ced_category_request_quote_comments');

        if (version_compare($context->getVersion(), '0.0.5', '<')) {
            if ($installer->getConnection()->isTableExists($itemtableName) == true) {
                $connection = $setup->getConnection();
                $connection
                    ->addColumn(
                        $itemtableName,
                        'product_id',
                        [
                            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                            'length' => 10,
                            'nullable' => false,
                            'comment' => 'Product Id'
                        ]
                    );
            }
        }

        if (version_compare($context->getVersion(), '0.0.8', '<')) {
            $tableName = $installer->getTable('ced_category_request_quote');
            if ($installer->getConnection()->isTableExists($tableName) == true) {
                $connection = $setup->getConnection();
                $connection
                    ->addColumn(
                        $tableName,
                        'product_id',
                        [
                            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                            'length' => 10,
                            'nullable' => false,
                            'comment' => 'Product Id'
                        ]
                    );
            }
        }

        if (version_compare($context->getVersion(), '0.0.9', '<')) {
            $tableName = $installer->getTable('ced_category_request_quote');
            if ($installer->getConnection()->isTableExists($tableName) == true) {
                $connection = $setup->getConnection();
                $connection
                    ->addColumn(
                        $tableName,
                        'negotiated_final_qty',
                        [
                            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                            'length' => 10,
                            'nullable' => false,
                            'comment' => 'Negotiated Final Quantity'
                        ]
                    );
            }
        }

        if (version_compare($context->getVersion(), '0.0.9', '<')) {
            $tableName = $installer->getTable('ced_category_request_quote_comments');
            if ($installer->getConnection()->isTableExists($tableName) == true) {
                $connection = $setup->getConnection();
                $connection
                    ->addColumn(
                        $tableName,
                        'vendor_id',
                        [
                            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                            'length' => 10,
                            'nullable' => false,
                            'comment' => 'Vendor Id'
                        ]
                    );
                $connection->addColumn(
                        $tableName,
                        'created_at',
                        [
                            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                            'length' => null,
                            'nullable' => false,
                            'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT,
                            'comment' => 'Created At'
                        ]
                    );
            }
        }

        if (version_compare($context->getVersion(), '0.0.9', '<')) {
            $tableName = $installer->getTable('ced_category_request_quote_vendors');
            if ($installer->getConnection()->isTableExists($tableName) == true) {
                $connection = $setup->getConnection();
                $connection
                    ->addColumn(
                        $tableName,
                        'is_approved',
                        [
                            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                            'length' => null,
                            'nullable' => false,
                            'comment' => 'Is Approved'
                        ]
                    );
                $connection->addColumn(
                        $tableName,
                        'created_at',
                        [
                            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                            'length' => null,
                            'nullable' => false,
                            'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT,
                            'comment' => 'Created At'
                        ]
                    );
            }
        }

        if (version_compare($context->getVersion(), '0.0.11', '<')) {
            $tableName = $installer->getTable('ced_category_request_quote');
            if ($installer->getConnection()->isTableExists($tableName) == true) {
                $connection = $setup->getConnection();
                $connection
                    ->addColumn(
                        $tableName,
                        'quote_item_id',
                        [
                            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                            'length' => 10,
                            'nullable' => true,
                            'comment' => 'Quote Item Id'
                        ]
                    );

                $connection
                    ->addColumn(
                        $tableName,
                        'customer_email',
                        [
                            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                            'length' => 255,
                            'nullable' => true,
                            'comment' => 'Customer Email'
                        ]
                    );
            }
        }

        if (version_compare($context->getVersion(), '0.0.12', '<')) {
            $tableName = $installer->getTable('ced_category_request_quote_vendors');
            if ($installer->getConnection()->isTableExists($tableName) == true) {
                $connection = $setup->getConnection();
                $connection
                    ->addColumn(
                        $tableName,
                        'negotiation_qty',
                        [
                            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                            'length' => '11',
                            'nullable' => true,
                            'comment' => 'Negotiation Quantity'
                        ]
                    );
                $connection->addColumn(
                    $tableName,
                    'negotiation_price',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                        'length' => '20,4',
                        'nullable' => true,
                        'comment' => 'Negotiation Price'
                    ]
                );

                $connection->addColumn(
                    $tableName,
                    'product_name',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => '255',
                        'nullable' => true,
                        'comment' => 'Product Name'
                    ]
                );
            }
        }

        if (version_compare($context->getVersion(), '0.0.13', '<')) {
            $tableName = $installer->getTable('ced_category_request_quote_vendors');
            if ($installer->getConnection()->isTableExists($tableName) == true) {
                $connection = $setup->getConnection();
                $connection
                    ->addColumn(
                        $tableName,
                        'vendor_replied',
                        [
                            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                            'length' => null,
                            'nullable' => true,
                            'comment' => 'Vendor Replied'
                        ]
                    );
            }
        }

        if (version_compare($context->getVersion(), '0.0.14', '<')) {
            $tableName = $installer->getTable('ced_category_request_quote_vendors');
            if ($installer->getConnection()->isTableExists($tableName) == true) {
                $connection = $setup->getConnection();
                $connection
                    ->addColumn(
                        $tableName,
                        'product_id',
                        [
                            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                            'length' => 10,
                            'nullable' => true,
                            'comment' => 'Product Id'
                        ]
                    );
            }
        }

        if (version_compare($context->getVersion(), '0.0.14', '<')) {
            $tableName = $installer->getTable('ced_category_request_quote');
            if ($installer->getConnection()->isTableExists($tableName) == true) {
                $connection = $setup->getConnection();
                $connection->addColumn(
                    $tableName,
                    'product_name',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => '255',
                        'nullable' => true,
                        'comment' => 'Product Name'
                    ]
                );
            }
        }

        if (version_compare($context->getVersion(), '0.0.15', '<')) {
            $tableName = $installer->getTable('ced_category_request_quote_history');
            if ($installer->getConnection()->isTableExists($tableName) == true) {
                $connection = $setup->getConnection();
                $connection->addColumn(
                    $tableName,
                    'created_at',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        'length' => null,
                        'nullable' => false,
                        'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT,
                        'comment' => 'Created At'
                    ]
                );
            }
        }
        $installer->endSetup();
    }
}
