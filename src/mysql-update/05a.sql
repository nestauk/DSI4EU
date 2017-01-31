ALTER TABLE `auth-tokens` CHANGE `token` `token` CHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE `auth-tokens` CHANGE `selector` `selector` CHAR(32) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE `auth-tokens` ADD `ip` CHAR(32) NOT NULL AFTER `userID`;

ALTER TABLE `auth-tokens` DROP `id`;

ALTER TABLE `auth-tokens` ADD PRIMARY KEY (`selector`);