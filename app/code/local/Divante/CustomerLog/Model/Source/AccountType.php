<?php
/**
 * Created by PhpStorm.
 * User: Marek
 * Date: 2015-02-19
 * Time: 11:04
 */
class Divante_CustomerLog_Model_Source_AccountType
{
    const ROOT = 0;
    const ADMIN = 1;
    const CUSTOMER = 2;

    public function toOptionHash()
    {
        return array(
            self::ROOT => 'Root',
            self::ADMIN => 'Administrator',
            self::CUSTOMER => 'Użytkownik',
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