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

namespace Ced\CsMarketplace\Model\ResourceModel\Vendor\Attribute;

/**
 * Class Collection
 * @package Ced\CsMarketplace\Model\ResourceModel\Vendor\Attribute
 */
class Collection extends \Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection
{

    /**
     * @var \Magento\Eav\Model\Entity
     */
    protected $eavEntity;

    /**
     * Collection constructor.
     * @param \Magento\Eav\Model\Entity $eavEntity
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Eav\Model\Config $eavConfig
     * @param \Magento\Framework\DB\Adapter\AdapterInterface|null $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb|null $resource
     */
    public function __construct(
        \Magento\Eav\Model\Entity $eavEntity,
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        $this->eavEntity = $eavEntity;
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $eavConfig, $connection, $resource);
    }

    /**
     * Specify attribute entity type filter.
     * Entity type is defined.
     *
     * @param  int $typeId
     * @return Collection
     */
    public function setEntityTypeFilter($typeId)
    {
        return $this;
    }

    /**
     * Resource model initialization
     */
    protected function _construct()
    {
        $this->_init('Ced\CsMarketplace\Model\Vendor\Attribute', 'Magento\Eav\Model\ResourceModel\Entity\Attribute');
    }

    /**
     * initialize select object
     * @return $this|void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _initSelect()
    {
        $entityTypeId = (int)$this->eavEntity->setType('csmarketplace_vendor')->getTypeId();
        $columns = $this->getConnection()->describeTable($this->getResource()->getMainTable());
        unset($columns['attribute_id']);
        $retColumns = [];
        foreach ($columns as $labelColumn => $columnData) {
            $retColumns[$labelColumn] = $labelColumn;
            if ($columnData['DATA_TYPE'] == \Magento\Framework\DB\Ddl\Table::TYPE_TEXT) {
                $retColumns[$labelColumn] = 'main_table.' . $labelColumn;
            }
        }

        $this->getSelect()
            ->from(['main_table' => $this->getResource()->getMainTable()], $retColumns)
            ->join(
                ['additional_table' => $this->getTable('ced_csmarketplace_vendor_form_attribute')],
                'additional_table.attribute_id = main_table.attribute_id'
            )
            ->where('main_table.entity_type_id = ?', $entityTypeId);

    }

    /**
     * Return array of fields to load attribute values
     *
     * @return array
     */
    protected function _getLoadDataFields()
    {
        $fields = array_merge(
            parent::_getLoadDataFields(),
            [
                'additional_table.is_global',
                'additional_table.is_html_allowed_on_front',
                'additional_table.is_wysiwyg_enabled'
            ]
        );

        return $fields;
    }
}
