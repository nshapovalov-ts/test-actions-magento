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

namespace Ced\CsProduct\Controller\Vproducts\Initialization;

use Magento\CatalogInventory\Api\StockConfigurationInterface;

class StockDataFilter
{
    /**
     * The greatest value which could be stored in CatalogInventory Qty field
     */
    const MAX_QTY_VALUE = 99999999;

    /**
     * @var StockConfigurationInterface
     */
    protected $stockConfiguration;

    /**
     * @param StockConfigurationInterface $stockConfiguration
     */
    public function __construct(
        StockConfigurationInterface $stockConfiguration
    ) {
        $this->stockConfiguration = $stockConfiguration;
    }

    /**
     * Filter stock data
     *
     * @param array $stockData
     * @return array
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function filter(array $stockData)
    {
        if (!isset($stockData['use_config_manage_stock'])) {
            $stockData['use_config_manage_stock'] = 0;
        }

        if ($stockData['use_config_manage_stock'] == 1 && !isset($stockData['manage_stock'])) {
            $stockData['manage_stock'] = $this->stockConfiguration->getManageStock();
        }
        if (isset($stockData['qty']) && (double)$stockData['qty'] > self::MAX_QTY_VALUE) {
            $stockData['qty'] = self::MAX_QTY_VALUE;
        }

        if (isset($stockData['min_qty']) && (int)$stockData['min_qty'] < 0) {
            $stockData['min_qty'] = 0;
        }

        if (!isset($stockData['is_decimal_divided']) || $stockData['is_qty_decimal'] == 0) {
            $stockData['is_decimal_divided'] = 0;
        }

        return $stockData;
    }
}
