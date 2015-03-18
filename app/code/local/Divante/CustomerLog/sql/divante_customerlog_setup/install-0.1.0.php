<?php
/**
 * Created by PhpStorm.
 * User: Marek
 * Date: 2015-02-18
 * Time: 12:56
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/** @var Mage_Eav_Model_Entity_Setup $setup */
//$setup = Mage::getModel('eav/entity_setup', 'core_setup');
//
//$entityTypeId = $setup->getEntityTypeId('customer');
//$attributeSetId = $setup->getDefaultAttributeSetId($entityTypeId);
//$attributeGroupId = $setup->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);
//
//$usedInForm = 'adminhtml_customer';
//$attributes = array(
//    'created_by_username' => 'Konto utworzył',
//    'created_by_full_name' => 'Konto utworzył - pełna nazwa',
//    'created_by_email' => 'Konto utworzył - email',
//    'created_by_ip' => 'Konto utworzył - ip',
//    'created_by_account_type' => 'Konto utworzył - typ użytkownika',
//);
//
//foreach($attributes as $attributeCode => $label) {
//    $setup->addAttribute('customer', $attributeCode, array(
//        'type' => 'varchar',
//        'backend' => '',
//        'label' => $label,
//        'input' => 'text',
//        'source' => '',
//        'visible' => false,
//        'required' => false,
//        'default' => '',
//        'frontend' => '',
//        'unique' => false,
//        'adminhtml_only' => 1,
//    ));
//
//    $setup->addAttributeToGroup(
//        $entityTypeId,
//        $attributeSetId,
//        $attributeGroupId,
//        $attributeCode,
//        '100'
//    );
//}

$installer->run("
CREATE TABLE IF NOT EXISTS `customer_log` (
  `log_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` INT(10) UNSIGNED NULL,
  `username` VARCHAR(64) NOT NULL,
  `user_email` VARCHAR(64) NOT NULL,
  `user_full_name` VARCHAR(64) NOT NULL,
  `user_ip` BIGINT(20) NULL,
  `action_type` TINYINT(1) UNSIGNED NOT NULL,
  `account_type` TINYINT(1) UNSIGNED NOT NULL,
  `data_type` TINYINT(1) UNSIGNED NOT NULL,
  `new_data` TEXT NOT NULL,
  `old_data` TEXT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`),
  INDEX `fk_customer_log_customer_entity_idx` (`customer_id` ASC),
  CONSTRAINT `fk_customer_log_customer_entity`
    FOREIGN KEY (`customer_id`)
    REFERENCES `customer_entity` (`entity_id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();