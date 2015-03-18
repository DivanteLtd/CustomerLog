<?php
/**
 * Created by PhpStorm.
 * User: Marek
 * Date: 2015-02-19
 * Time: 13:27
 */
class Divante_CustomerLog_Block_Adminhtml_Customer_Edit_Tab_Log extends Mage_Core_Block_Abstract implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected function _toHtml()
    {
        $createdByInfo = $this->getLayout()->createBlock('divante_customerlog/adminhtml_customer_edit_tab_log_info');
        $logGrid = $this->getLayout()->createBlock('divante_customerlog/adminhtml_customer_edit_tab_log_list');
        return $createdByInfo->toHtml() . $logGrid->toHtml();
    }

    /**
     * Retrieve the label used for the tab relating to this block
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->__('Log zmian');
    }

    /**
     * Retrieve the title used by this tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('Log zmian');
    }
    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    public function getTabUrl()
    {
        return $this->getUrl('*/customer_log/customerTab', array('_current' => true));
    }

    public function getSkipGenerateContent()
    {
        return true;
    }

    public function getTabClass()
    {
        return 'ajax';
    }

    /**
     * Stops the tab being hidden
     *
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }
}