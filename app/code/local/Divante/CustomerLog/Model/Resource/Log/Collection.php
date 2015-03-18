<?php
/**
 * Created by PhpStorm.
 * User: Marek
 * Date: 2015-02-19
 * Time: 11:04
 */ 
class Divante_CustomerLog_Model_Resource_Log_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{

    protected function _construct()
    {
        $this->_init('divante_customerlog/log');
    }

}