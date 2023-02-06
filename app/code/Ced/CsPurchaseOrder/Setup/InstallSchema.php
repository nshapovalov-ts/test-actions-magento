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

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class InstallSchema
 * @package Ced\CsPurchaseOrder\Setup
 */
class InstallSchema implements InstallSchemaInterface
{

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        if (!$installer->getConnection()->isTableExists('ced_category_request_quote_vendor_category')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('ced_category_request_quote_vendor_category')
            )->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )->addColumn(
                'vendor_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['unsigned' => true, 'nullable' => false],
                'Vendor Id'
            )->addColumn(
                'category_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                '10',
                ['unsigned' => true, 'nullable' => false],
                'Category Id'
            );
            $installer->getConnection()->createTable($table);
        }

        if (!$installer->getConnection()->isTableExists('ced_category_request_quote')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('ced_category_request_quote')
            )->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )->addColumn(
                'title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Title'
            )->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '50',
                [],
                'Status'
            )->addColumn(
                'category_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                '10',
                [],
                'Category Id'
            )->addColumn(
                'proposed_qty',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                '10',
                [],
                'Proposed Quantity'
            )->addColumn(
                'preferred_price_per_qty',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                '20,4',
                [],
                'Preferred Price Per Quantity'
            )->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Created At'
            )->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                'Updated At'
            )->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                5,
                ['default' => 0, 'unsigned' => true],
                'Store Id'
            )->addColumn(
                'is_active',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                5,
                ['default' => 1, 'unsigned' => true],
                'Is Active'
            )->addColumn(
                'customer_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                [],
                'Customer Id'
            )->addColumn(
                'remote_ip',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                45,
                [],
                'Remote Ip'
            )->addColumn(
                'base_preferred_price',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                '20,4',
                [],
                'Base Preferred Price'
            )->addColumn(
                'order_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['unsigned' => true],
                'Order Id'
            )->addColumn(
                'description',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                [],
                'Description'
            )->addColumn(
                'terms_and_conditions',
                \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                null,
                [],
                'Terms And Conditions'
            )->addColumn(
                'negotiated_final_price',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                '20,4',
                [],
                'Negotiated Final Price'
            );
            $installer->getConnection()->createTable($table);
        }

        if (!$installer->getConnection()->isTableExists('ced_category_request_quote_attachments')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('ced_category_request_quote_attachments')
            )->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )->addColumn(
                'c_quote_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['unsigned' => true],
                'Quote Id'
            )->addColumn(
                'file_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Attachment File Name'
            )->addColumn(
                'file_path',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                [],
                'Path To File'
            )->addColumn(
                'file_type',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                [],
                'File Type'
            );
            $installer->getConnection()->createTable($table);
        }

        if (!$installer->getConnection()->isTableExists('ced_category_request_quote_comments')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('ced_category_request_quote_comments')
            )->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )->addColumn(
                'c_quote_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['unsigned' => true],
                'Quote Id'
            )->addColumn(
                'author_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['unsigned' => true],
                'Author Id'
            )->addColumn(
                'negotiation_qty',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                [],
                'Negotiation Quantity'
            )->addColumn(
                'negotiation_price',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                '20,4',
                [],
                'Negotiation Price'
            )->addColumn(
                'comments',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                [],
                'Comments'
            )->addColumn(
                'who_is',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                5,
                [],
                'Who is (0. Customer, 1. Vendor, 2. Admin)'
            );
            $installer->getConnection()->createTable($table);
        }

        if (!$installer->getConnection()->isTableExists('ced_category_request_quote_history')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('ced_category_request_quote_history')
            )->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )->addColumn(
                'c_quote_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['unsigned' => true],
                'Quote Id'
            )->addColumn(
                'vendor_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['unsigned' => true],
                'Vendor Id'
            )->addColumn(
                'author_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                [],
                'Author Id'
            )->addColumn(
                'who_is',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                5,
                [],
                'Who is (0. Customer, 1. Vendor, 2. Admin)'
            )->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                50,
                [],
                'Status'
            )->addColumn(
                'log_data',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                [],
                'Log Data'
            );
            $installer->getConnection()->createTable($table);
        }
        if (!$installer->getConnection()->isTableExists('ced_category_request_quote_vendors')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('ced_category_request_quote_vendors')
            )->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )->addColumn(
                'c_quote_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['unsigned' => true],
                'Quote Id'
            )->addColumn(
                'vendor_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['unsigned' => true],
                'Vendor Id'
            )->addColumn(
                'vendor_status',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                50,
                [],
                'Vendor Status(Active, Pending, Processing, Reject, Approve, Disapprove)'
            )->addColumn(
                'who_is',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                5,
                [],
                'Who is (0. Customer, 1. Vendor, 2. Admin)'
            )->addColumn(
                'author_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                [],
                'Author Id'
            );
            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}
