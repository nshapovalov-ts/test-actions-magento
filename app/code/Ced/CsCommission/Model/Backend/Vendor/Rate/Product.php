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
 * @category  Ced
 * @package   Ced_CsCommission
 * @author    CedCommerce Core Team <connect@cedcommerce.com >
 * @copyright Copyright CedCommerce (https://cedcommerce.com/)
 * @license      https://cedcommerce.com/license-agreement.txt
 */

namespace Ced\CsCommission\Model\Backend\Vendor\Rate;

class Product extends \Magento\Framework\App\Config\Value
{
    /**
     * @var \Ced\CsCommission\Helper\Product
     */
    protected $_productHelper;

    /**
     * Product constructor.
     * @param \Ced\CsCommission\Helper\Product $commissionProductHelper
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Ced\CsCommission\Helper\Product $commissionProductHelper,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_productHelper = $commissionProductHelper;
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    /**
     * @return $this|\Magento\Framework\App\Config\Value|string
     */
    public function _afterLoad()
    {
        $value = $this->getValue();
        $arr = '';
        if ($value != '') {
            $arr = json_decode($value, true);
        }

        if (!is_array($arr)) {
            return '';
        }

        $sortOrder = [];
        $cnt = 1;
        foreach ($arr as $k => $val) {
            if (!is_array($val)) {
                unset($arr[$k]);
                continue;
            }
            $sortOrder[$k] = $val['priority'] ?? $cnt++;
        }
        //sort by priority
        array_multisort($sortOrder, SORT_ASC, $arr);
        $this->setValue($arr);
        return $this;
    }

    /**
     * Prepare data before save
     */
    public function beforeSave()
    {
        $value = $this->getValue();
        $value = $this->_productHelper->getSerializedOptions($value);
        $this->setValue($value);
        return parent::beforeSave();
    }
}
