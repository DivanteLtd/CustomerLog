<?php
/**
 * Created by PhpStorm.
 * User: Marek
 * Date: 2015-02-20
 * Time: 12:50
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("
ALTER TABLE `customer_log` DROP `new_data`;
ALTER TABLE `customer_log` DROP `old_data`;
CREATE TABLE IF NOT EXISTS `customer_log_values` (
  `log_id` INT(10) UNSIGNED NOT NULL,
  `attribute_code` VARCHAR(255) NOT NULL,
  `old_value` VARCHAR(255) NULL,
  `new_value` VARCHAR(255) NULL,
  INDEX `fk_customer_log_values_customer_log_idx` (`log_id` ASC),
  PRIMARY KEY (`log_id`, `attribute_code`),
  CONSTRAINT `fk_customer_log_values_customer_log`
    FOREIGN KEY (`log_id`)
    REFERENCES `customer_log` (`log_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();