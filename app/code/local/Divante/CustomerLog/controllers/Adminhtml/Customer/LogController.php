<?php
/**
 * Created by PhpStorm.
 * User: Marek
 * Date: 2015-02-19
 * Time: 15:04
 */
class Divante_CustomerLog_Adminhtml_Customer_LogController extends Mage_Adminhtml_Controller_Action
{
    protected function _initCustomer($idFieldName = 'id')
    {
        $this->_title($this->__('Customers'))->_title($this->__('Manage Customers'));

        $customerId = (int) $this->getRequest()->getParam($idFieldName);
        $customer = Mage::getModel('customer/customer');

        if ($customerId) {
            $customer->load($customerId);
        }

        Mage::register('current_customer', $customer);
        return $this;
    }

    protected function _initLog()
    {
        $logId = $this->getRequest()->getParam('log_id');
        $log = Mage::getModel('divante_customerlog/log')->load($logId);

        Mage::register('customer_log', $log);
    }

    public function detailsAction()
    {
        $this->_initLog();
        $this->loadLayout();
        $this->renderLayout();
    }

    public function customerTabAction()
    {
        $this->_initCustomer();
        $this->loadLayout();
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->_initCustomer();
        $this->loadLayout();
        $this->renderLayout();
    }
}