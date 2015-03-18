<?php
/**
 * Created by PhpStorm.
 * User: Marek
 * Date: 2015-02-19
 * Time: 15:02
 */
class Divante_CustomerLog_Block_Adminhtml_Customer_Edit_Tab_Log_Details_Changes extends Mage_Core_Block_Template
{
    /**
     * @return Divante_CustomerLog_Model_Log
     */
    public function getLog()
    {
        return Mage::registry('customer_log');
    }

    public function getLogData()
    {
        $result = array();
        $oldData = $this->getLog()->getOldData();
        $newData = $this->getLog()->getNewData();

        foreach($oldData as $key => $value) {
            $data = array();
            $data['old_value'] = $value;

            if(isset($newData[$key])) {
                $data['new_value'] = $newData[$key];
            } else {
                $data['new_value'] = '';
            }

            $result[$key] = $data;
        }

        foreach($newData as $key => $value) {
            if(!isset($result[$key])) {
                $result[$key] = array(
                    'old_value' => '',
                    'new_value' => $value
                );
            }
        }

        return $result;
    }
}