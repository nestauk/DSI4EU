ALTER TABLE `organisations` ADD `isPublished` TINYINT(1) NOT NULL AFTER `headerImage`;

CREATE TABLE `terminate-account-tokens` (
  `userID` int(10) UNSIGNED NOT NULL,
  `token` char(255) NOT NULL,
  `expires` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `terminate-account-tokens`
  ADD UNIQUE KEY `token` (`token`);
