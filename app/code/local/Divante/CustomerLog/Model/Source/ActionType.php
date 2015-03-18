<?php
/**
 * Created by PhpStorm.
 * User: Marek
 * Date: 2015-02-19
 * Time: 11:05
 */
class Divante_CustomerLog_Model_Source_ActionType
{
    const NEW_ENTITY = 1;
    const UPDATE = 2;
    const DELETE = 3;

    public function toOptionHash()
    {
        return array(
            self::NEW_ENTITY => 'Nowy',
            self::UPDATE => 'Aktualizacja',
            self::DELETE => 'Usunięcie',
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