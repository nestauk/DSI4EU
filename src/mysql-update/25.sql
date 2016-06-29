ALTER TABLE `organisation-types` ADD `order` TINYINT UNSIGNED NOT NULL AFTER `name`;
ALTER TABLE `organisation-sizes` ADD `order` TINYINT UNSIGNED NOT NULL AFTER `name`;