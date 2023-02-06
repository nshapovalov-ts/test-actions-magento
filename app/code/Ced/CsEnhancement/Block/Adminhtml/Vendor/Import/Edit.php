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
 * @package   Ced_CsEnhancement
 * @author    CedCommerce Core Team <connect@cedcommerce.com >
 * @copyright Copyright CedCommerce (https://cedcommerce.com/)
 * @license      https://cedcommerce.com/license-agreement.txt
 */

namespace Ced\CsEnhancement\Block\Adminhtml\Vendor\Import;

/**
 * Class Edit
 * @package Ced\CsEnhancement\Block\Adminhtml\Vendor\Import
 */
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Get header text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        return __('Import Vendors');
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->buttonList->remove('reset');
        $this->buttonList->update(
            'back',
            'onclick',
            'setLocation(\'' . $this->getUrl('csmarketplace/vendor/') . '\')'
        );
        $this->buttonList->update('save', 'label', __('Import'));
        $this->buttonList->update('save', 'id', 'import');
        $this->buttonList->update('save', 'data_attribute', '');
        $this->buttonList->update('save', 'style', 'display:none');
        $this->buttonList->update('save', 'onclick', 'vendorImport.import()');
        $this->buttonList->add('csv_format', ['label' => __('Export Csv Format'), 'class' => 'secondary', 'onclick' => 'vendorImport.export()']);
        $this->buttonList->add('upload_button', ['label' => __('Check Data'), 'class' => 'primary', 'onclick' => 'vendorImport.validate()', 'style' => 'display:none']);
        $this->_objectId = 'import_id';
        $this->_blockGroup = 'Ced_CsEnhancement';
        $this->_controller = 'adminhtml_vendor_import';
    }
}
