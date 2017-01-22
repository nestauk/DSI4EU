ALTER TABLE `dsi-focus-tags` ADD `description` VARCHAR(255) NOT NULL AFTER `tag`;
UPDATE `dsi-focus-tags` SET `description` = 'Making things with open hardware to tackle social challenges' WHERE `dsi-focus-tags`.`id` = 35;
UPDATE `dsi-focus-tags` SET `description` = 'Making things with open hardware to tackle social challengesHarnessing the power and assets of the crowd to tackle social challenges' WHERE `dsi-focus-tags`.`id` = 4;
UPDATE `dsi-focus-tags` SET `description` = 'Capturing, sharing, analysing and using open data to tackle social challenges' WHERE `dsi-focus-tags`.`id` = 8;
UPDATE `dsi-focus-tags` SET `description` = 'Growing networks and infrastructure through technology from the bottom up to tackle social challenges' WHERE `dsi-focus-tags`.`id` = 9;
