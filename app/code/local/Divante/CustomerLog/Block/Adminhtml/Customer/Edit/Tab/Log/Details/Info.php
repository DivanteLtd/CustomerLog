<?php
/**
 * Created by PhpStorm.
 * User: Marek
 * Date: 2015-02-19
 * Time: 16:01
 */
class Divante_CustomerLog_Block_Adminhtml_Customer_Edit_Tab_Log_Details_Info extends Mage_Adminhtml_Block_Template
{
    protected $_customer;

    protected function _beforeToHtml()
    {
        $backButton = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
                'label' => $this->__('Wstecz'),
                'class' => 'back',
                'onclick' => 'setLocation(\'' .$this->_getBackUrl().'\')'
            ));
        
        $this->setChild('back_button', $backButton);

        return parent::_beforeToHtml();
    }

    protected function _getBackUrl()
    {
        return $this->getUrl('*/customer/edit', array('id' => $this->getLog()->getCustomerId(), 'tab' => 'divante_customer_log_tab'));
    }

    /**
     * @return Divante_CustomerLog_Model_Log
     */
    public function getLog()
    {
        return Mage::registry('customer_log');
    }

    /**
     * @return Mage_Customer_Model_Customer
     */
    public function getCustomer()
    {
        if(is_null(Mage::registry('current_customer'))) {
            $customer = Mage::getModel('customer/customer')->load($this->getLog()->getCustomerId());
            Mage::register('current_customer', $customer);
        }

        return Mage::registry('current_customer');
    }

    public function getCreatedAt()
    {
        $helper = Mage::helper('core');

        return $helper->formatDate($this->getLog()->getCreatedAt(), null, true);
    }

    public function getActionTypeLabel()
    {
        $actionTypes = Mage::getSingleton('divante_customerlog/source_actionType')->toOptionHash();

        return isset($actionTypes[$this->getLog()->getActionType()]) ? $actionTypes[$this->getLog()->getActionType()] : $this->getLog()->getActionType();
    }

    public function getDataTypeLabel()
    {
        $dataTypes = Mage::getSingleton('divante_customerlog/source_dataType')->toOptionHash();

        return isset($dataTypes[$this->getLog()->getDataType()]) ? $dataTypes[$this->getLog()->getDataType()] : $this->getLog()->getDataType();
    }
}