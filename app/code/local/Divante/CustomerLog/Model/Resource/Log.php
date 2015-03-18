<?php
/**
 * Created by PhpStorm.
 * User: Marek
 * Date: 2015-02-19
 * Time: 11:04
 */ 
class Divante_CustomerLog_Model_Resource_Log extends Mage_Core_Model_Resource_Db_Abstract
{

    protected function _construct()
    {
        $this->_init('divante_customerlog/log', 'log_id');
    }

    /**
     * @param int|Divante_CustomerLog_Model_Log $log
     * @return array
     */
    public function getLogValues($log)
    {
        if($log instanceof Divante_CustomerLog_Model_Log) {
            if(!$log->getId()) {
                return array();
            }

            $log = $log->getId();
        }

        $adapter = $this->_getReadAdapter();
        $select = $adapter->select();
        $select->from($this->getTable('divante_customerlog/log_values'), array('attribute_code', 'old_value', 'new_value'));

        $select->where('log_id = ?', $log);

        return $adapter->fetchAssoc($select);
    }

    /**
     * @param int|Divante_CustomerLog_Model_Log $log
     * @param array $values
     * @return $this
     */
    public function saveLogValues($log, $values)
    {
        if($log instanceof Divante_CustomerLog_Model_Log) {
            $log = $log->getId();
        }

        $adapter = $this->_getWriteAdapter();
        $tableName = $this->getTable('divante_customerlog/log_values');

        $adapter->delete($tableName, array('log_id = ?' => $log));

        if(!empty($values)) {
            $insertArray = array();

            foreach($values as $key => $value) {
                $oldValue = isset($value['old_value']) ? $value['old_value'] : '';
                $newValue = isset($value['new_value']) ? $value['new_value'] : '';
                $insertArray[] = array($log, $key, $oldValue, $newValue);
            }

            Mage::log($insertArray);

            if(!empty($insertArray)) {
                $adapter->insertArray($tableName, array('log_id', 'attribute_code', 'old_value', 'new_value'), $insertArray);
            }

        }

        return $this;
    }

}