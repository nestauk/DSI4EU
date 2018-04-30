ALTER TABLE `projects` ADD `isWaitingApproval` TINYINT(1) NOT NULL DEFAULT '0' AFTER `socialImpact`;

ALTER TABLE `organisations` ADD `isWaitingApproval` TINYINT(1) NOT NULL DEFAULT '0' AFTER `headerImage`;