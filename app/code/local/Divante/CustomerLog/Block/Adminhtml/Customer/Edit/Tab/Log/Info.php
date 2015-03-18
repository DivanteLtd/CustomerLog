<?php
/**
 * Created by PhpStorm.
 * User: Marek
 * Date: 2015-02-19
 * Time: 13:35
 */
class Divante_CustomerLog_Block_Adminhtml_Customer_Edit_Tab_Log_Info extends Mage_Adminhtml_Block_Widget_Form
{
    /** @var Divante_CustomerLog_Model_Log */
    protected $_createdBy;

    protected function _construct()
    {
        parent::_construct();
        $this->setId('customer_log_form');
    }

    /**
     * @return Mage_Customer_Model_Customer
     */
    protected function _getCustomer()
    {
        return Mage::registry('current_customer');
    }

    protected function _getCreatedByLog()
    {
        if(is_null($this->_createdBy)) {
            $collection = Mage::getResourceModel('divante_customerlog/log_collection');
            $collection->addFieldToFilter('customer_id', array('eq' => $this->_getCustomer()->getId()));
            $collection->addFieldToFilter('data_type', array('eq' => constant(get_class(Mage::getSingleton('divante_customerlog/source_dataType')) . '::CUSTOMER')));
            $collection->addFieldToFilter('action_type', array('eq' => constant(get_class(Mage::getSingleton('divante_customerlog/source_actionType')) . '::NEW_ENTITY')));
            $collection->setPageSize(1);

            $this->_createdBy = $collection->getFirstItem();
        }

        return $this->_createdBy;
    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setUseContainer(false);
        $this->setForm($form);

        $personalData = $form->addFieldset('customer_log', array('legend' => $this->__('Informacje o użytkowniku, który utworzył konto')));

        $personalData->addField('created_by_username', 'text', array(
            'label'     => $this->__('Użytkownik'),
            'required'  => false,
            'name'      => 'created_by_username',
            'value'     => $this->_getCreatedByLog()->getUsername(),
            'disabled'  => true,
            'editable'  => false,
        ));

        $personalData->addField('created_by_full_name', 'text', array(
            'label'     => $this->__('Pełna nazwa użytkownika'),
            'required'  => false,
            'name'      => 'created_by_full_name',
            'value'     => $this->_getCreatedByLog()->getUserFullName(),
            'disabled'  => true,
            'editable'  => false,
        ));

        $personalData->addField('created_by_email', 'text', array(
            'label'     => $this->__('Email'),
            'required'  => false,
            'name'      => 'created_by_email',
            'value'     => $this->_getCreatedByLog()->getUserEmail(),
            'disabled'  => true,
            'editable'  => false,
        ));

        $personalData->addField('created_by_ip', 'text', array(
            'label'     => $this->__('IP'),
            'class'     => 'required-entry',
            'required'  => false,
            'name'      => 'created_by_ip',
            'value'     => $this->_getCreatedByLog()->getUserIp(),
            'disabled'  => true,
            'editable'  => false,
        ));

        $personalData->addField('created_by_account_type', 'select', array(
            'label'     => $this->__('Typ konta'),
            'class'     => 'required-entry',
            'required'  => false,
            'name'      => 'created_by_account_type',
            'value'     => $this->_getCreatedByLog()->getAccountType(),
            'disabled'  => true,
            'editable'  => false,
            'values'    => Mage::getSingleton('divante_customerlog/source_accountType')->toOptionArray()
        ));

        return parent::_prepareForm();
    }

    /**
     * @return Mage_Adminhtml_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('adminhtml/session');
    }
}