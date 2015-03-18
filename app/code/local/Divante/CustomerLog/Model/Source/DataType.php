<?php
/**
 * Created by PhpStorm.
 * User: Marek
 * Date: 2015-02-19
 * Time: 11:05
 */
class Divante_CustomerLog_Model_Source_DataType
{
    const CUSTOMER = 1;
    const ADDRESS = 2;

    public function toOptionHash()
    {
        return array(
            self::CUSTOMER => 'Customer_Model',
            self::ADDRESS => 'Address_Model'
        );
    }

    public function toOptionArray()
    {
        $result = array();

        foreach($this->toOptionHash() as $value => $label) {
            $result[] = array(
                'value' => $value,
                'label' => $label
            );
        }

        return $result;
    }
}