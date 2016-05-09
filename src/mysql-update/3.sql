ALTER TABLE `projects` ADD `status` ENUM('live','closed') NOT NULL AFTER `url`;
ALTER TABLE `projects` ADD `startDate` DATE NOT NULL AFTER `status`, ADD `endDate` DATE NOT NULL AFTER `startDate`;