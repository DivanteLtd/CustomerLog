<?php
/**
 * Created by PhpStorm.
 * User: Marek
 * Date: 2015-02-19
 * Time: 11:31
 */
class Divante_CustomerLog_Model_Observer
{
    public function customerSaveAfter(Varien_Event_Observer $observer)
    {
        $dataType = constant(get_class(Mage::getModel('divante_customerlog/source_dataType')) . '::CUSTOMER');
        $this->_saveModifiedData($observer->getCustomer(), $dataType);
    }

    public function customerAddressSaveAfter(Varien_Event_Observer $observer)
    {
        $dataType = constant(get_class(Mage::getModel('divante_customerlog/source_dataType')) . '::ADDRESS');
        $this->_saveModifiedData($observer->getCustomerAddress(), $dataType);
    }

    /**
     * @param Mage_Customer_Model_Customer|Mage_Customer_Model_Address $object
     * @param int $dataType
     * @param null $actionType
     */
    protected function _saveModifiedData($object, $dataType, $actionType = null)
    {
        $logData = array();

        if($object instanceof Mage_Customer_Model_Customer) {
            $logData['customer_id'] = $object->getEntityId();
        } elseif($object instanceof Mage_Customer_Model_Address) {
            $logData['customer_id'] = $object->getParentId();
        } else {
            Mage::throwException('Nieprawidłowy typ obiektu.');
        }


        if(PHP_SAPI == 'cli') {
            $shellUser = shell_exec('whoami');
            $logData['username'] = $shellUser;
            $logData['user_email'] = $shellUser;
            $logData['user_full_name'] = $shellUser;
            $logData['account_type'] = constant(get_class(Mage::getModel('divante_customerlog/source_accountType')) . '::ROOT');
        } elseif(Mage::app()->getStore()->isAdmin()) {
            /** @var Mage_Admin_Model_User $user */
            $user = Mage::getSingleton('admin/session')->getUser();

            $logData['username'] = $user->getUsername();
            $logData['user_email'] = $user->getEmail();
            $logData['user_full_name'] = $user->getFirstname() . ' ' . $user->getLastname();
            $logData['account_type'] = constant(get_class(Mage::getModel('divante_customerlog/source_accountType')) . '::ADMIN');
        } else {
            /** @var Mage_Customer_Model_Session $customerSession */
            $customerSession = Mage::getSingleton('customer/session');
            /** @var Mage_Customer_Model_Customer $user */
            if($customerSession->getCustomerId() == $logData['customer_id'] || !$customerSession->isLoggedIn()) {
                $user = $object instanceof Mage_Customer_Model_Customer ? $object : $object->getCustomer();
            } else {
                $user = Mage::getSingleton('customer/session')->getCustomer();
            }

            $logData['username'] = $user->getEmail();
            $logData['user_email'] = $user->getEmail();
            $logData['user_full_name'] = $user->getName();
            $logData['account_type'] = constant(get_class(Mage::getModel('divante_customerlog/source_accountType')) . '::CUSTOMER');
        }

        if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
            $logData['user_ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '') {
            $logData['user_ip'] = $_SERVER['REMOTE_ADDR'];
        } else {
            $logData['user_ip'] = '127.0.0.1';
        }

        if(is_null($actionType)) {
            if($object->isObjectNew() || is_null($object->getOrigData())) {
                $logData['action_type'] = constant(get_class(Mage::getModel('divante_customerlog/source_actionType')) . '::NEW_ENTITY');
            } else {
                $logData['action_type'] = constant(get_class(Mage::getModel('divante_customerlog/source_actionType')) . '::UPDATE');
            }
        } else {
            $logData['action_type'] = $actionType;
        }

        $logData['data_type'] = $dataType;

        $modifiedData = array();

        foreach($object->getData() as $key => $value) {

            if($object->getOrigData($key) != $value) {
                $data = array();
                $data['new_value'] = $value;
                $data['old_value'] = $object->getOrigData($key);

                $modifiedData[$key] = $data;
            }
        }

        if(!is_null($object->getOrigData())) {
            foreach($object->getOrigData() as $key => $value) {
                if(!isset($modifiedData[$key]) && $object->getData($key) != $value) {
                    $modifiedData[$key] = array(
                        'old_value' => $value,
                        'new_value' => ''
                    );
                }
            }
        }

        unset($modifiedData['dob_is_formated']);
        unset($modifiedData['updated_at']);
        unset($modifiedData['created_at']);

        if($object instanceof Mage_Customer_Model_Address) {
            unset($modifiedData['is_default_shipping']);
            unset($modifiedData['is_default_billing']);
            unset($modifiedData['is_customer_save_transaction']);
            unset($modifiedData['customer_id']);
            unset($modifiedData['post_index']);
            unset($modifiedData['store_id']);
        }

        if(isset($modifiedData['password']) || isset($modifiedData['password_hash'])) {
            unset($modifiedData['password_hash']);
            $modifiedData['password']['old_value'] = Mage::helper('divante_customerlog')->__('***********');
            $modifiedData['password']['new_value'] = Mage::helper('divante_customerlog')->__('***********');
        }

        if(count($modifiedData) > 0) {
            /** @var Divante_CustomerLog_Model_Log $log */
            $log = Mage::getModel('divante_customerlog/log');
            $log->setData($logData);
            $log->setValues($modifiedData);

            try {
                $log->save();
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
    }
}