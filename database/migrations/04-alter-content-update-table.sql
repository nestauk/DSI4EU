ALTER TABLE `content-updates` CHANGE `updated` `updated` ENUM('new','update') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'new';
