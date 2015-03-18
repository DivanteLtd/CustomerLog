<?php
/**
 * Created by PhpStorm.
 * User: Marek
 * Date: 2015-02-19
 * Time: 14:06
 */
class Divante_CustomerLog_Block_Adminhtml_Customer_Edit_Tab_Log_List extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Initialize grid params
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('divante_customer_log_grid');
        $this->setDefaultSort('log_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * Set the template for the block
     *
     */
    public function _construct()
    {
        parent::_construct();
    }

    /**
     * @return Mage_Customer_Model_Customer
     */
    protected function _getCustomer()
    {
        return Mage::registry('current_customer');
    }

    /**
     * Prepare related orders collection
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('divante_customerlog/log_collection');
        $collection->addFieldToFilter('customer_id', array('eq' => $this->_getCustomer()->getId()));
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('log_id', array(
            'header'    => $this->__('ID'),
            'type'      => 'number',
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'log_id',
        ));

        $this->addColumn('username', array(
            'header'    => $this->__('Użytkownik'),
            'index'     => 'username',
        ));

        $this->addColumn('user_full_name', array(
            'header'    => $this->__('Imię i nazwisko użytkownika'),
            'index'     => 'user_full_name',
        ));

        $this->addColumn('user_email', array(
            'header'    => $this->__('Email'),
            'index'     => 'user_email',
        ));

        $this->addColumn('user_ip', array(
            'header'    => $this->__('IP'),
            'index'     => 'user_ip',
            'getter'    => 'getUserIp'
        ));

        $this->addColumn('action_type', array(
            'header'    => $this->__('Typ akcji'),
            'index'     => 'action_type',
            'type'      => 'options',
            'options'   => Mage::getSingleton('divante_customerlog/source_actionType')->toOptionHash(),
        ));

        $this->addColumn('account_type', array(
            'header'    => $this->__('Typ użytkownika'),
            'index'     => 'account_type',
            'type'      => 'options',
            'options'   => Mage::getSingleton('divante_customerlog/source_accountType')->toOptionHash(),
        ));

        $this->addColumn('data_type', array(
            'header'    => $this->__('Typ zmodyfikowanych danych'),
            'index'     => 'data_type',
            'type'      => 'options',
            'options'   => Mage::getSingleton('divante_customerlog/source_dataType')->toOptionHash(),
        ));

        $this->addColumn('created_at', array(
            'header'    => $this->__('Data'),
            'index'     => 'created_at',
            'type'      => 'datetime'
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/customer_log/details', array('log_id' => $row->getId()));
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/customer_log/grid', array('_current'=>true));
    }
}