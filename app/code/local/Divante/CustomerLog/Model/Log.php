<?php
/**
 * Created by PhpStorm.
 * User: Marek
 * Date: 2015-02-19
 * Time: 11:04
 */
/**
 * Class Divante_CustomerLog_Model_Log
 * @method Divante_CustomerLog_Model_Log setLogId(int $value)
 * @method int getLogId()
 * @method Divante_CustomerLog_Model_Log setCustomerId(int $value)
 * @method int getCustomerId()
 * @method Divante_CustomerLog_Model_Log setUsername(string $value)
 * @method string getUsername()
 * @method Divante_CustomerLog_Model_Log setUserEmail(string $value)
 * @method string getUserEmail()
 * @method Divante_CustomerLog_Model_Log setUserFullName(string $value)
 * @method string getUserFullName()
 * @method Divante_CustomerLog_Model_Log setActionType(int $value)
 * @method int getActionType()
 * @method Divante_CustomerLog_Model_Log setAccountType(int $value)
 * @method int getAccountType()
 * @method Divante_CustomerLog_Model_Log setDataType(int $value)
 * @method int getDataType()
 * @method Divante_CustomerLog_Model_Log setCreatedAt(string $value)
 * @method string getCreatedAt()
 * @method Divante_CustomerLog_Model_Resource_Log _getResource()
 */
class Divante_CustomerLog_Model_Log extends Mage_Core_Model_Abstract
{
    protected $_values;

    protected $_hasValuesUpdated = false;

    protected function _construct()
    {
        $this->_init('divante_customerlog/log');
    }

    public function setUserIp($ip)
    {
        if(!is_numeric($ip)) {
            if(!$ip = ip2long($ip)) {
                Mage::throwException('IP Address is not valid.');
            }
        }

        $this->setData('user_ip', $ip);

        return $this;
    }

    public function getUserIp()
    {
        if(is_numeric($this->getData('user_ip'))) {
            return long2ip($this->getData('user_ip'));
        }

        return $this->getData('user_ip');
    }

    /**
     * @return array
     */
    public function getLogValues()
    {
        if(is_null($this->_values)) {
            $this->_values = $this->_getResource()->getLogValues($this);
        }

        return $this->_values;
    }

    /**
     * @param array $values
     * @return $this
     */
    public function setValues($values)
    {
        $this->_hasValuesUpdated = true;
        $this->unsetData('old_data');
        $this->unsetData('new_data');
        $this->_values = $values;
        return $this;
    }

    /**
     * @return array
     */
    public function getOldData()
    {
        if(!$this->hasData('old_data')) {
            $values = $this->getLogValues();
            $data = array();

            foreach($values as $key => $value) {
                $data[$key] = $value['old_value'];
            }

            $this->setData('old_data', $data);
        }

        return $this->getData('old_data');
    }

    /**
     * @return array
     */
    public function getNewData()
    {
        if(!$this->hasData('new_data')) {
            $values = $this->getLogValues();
            $data = array();

            foreach($values as $key => $value) {
                $data[$key] = $value['new_value'];
            }

            $this->setData('new_data', $data);
        }

        return $this->getData('new_data');
    }

    protected function _beforeSave()
    {
        if(!is_numeric($this->getUserIp())) {
            $this->setUserIp($this->getUserIp());
        }

        return parent::_beforeSave();
    }

    protected function _afterSave()
    {
        if($this->_hasValuesUpdated) {
            $this->_getResource()->saveLogValues($this, $this->getLogValues());
        }

        return parent::_afterSave();
    }
}