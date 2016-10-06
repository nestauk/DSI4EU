ALTER TABLE `fundings` ADD `typeID` INT UNSIGNED NOT NULL AFTER `countryID`;
ALTER TABLE `fundings` ADD `targets` CHAR(255) NOT NULL AFTER `typeID`;