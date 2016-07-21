ALTER TABLE `organisations` ADD `url` VARCHAR(1000) NOT NULL AFTER `name`;
ALTER TABLE `organisations` ADD `startDate` DATE NOT NULL AFTER `organisationSizeID`;
ALTER TABLE `organisations` ADD `shortDescription` VARCHAR(10000) NOT NULL AFTER `url`;
ALTER TABLE `organisations` ADD `logo` CHAR(255) NOT NULL AFTER `startDate`
, ADD `headerImage` CHAR(255) NOT NULL AFTER `logo`;

CREATE TABLE `organisation-links` (
  `linkID` int(10) UNSIGNED NOT NULL,
  `organisationID` int(10) UNSIGNED NOT NULL,
  `link` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `organisation-links`
  ADD PRIMARY KEY (`linkID`);

ALTER TABLE `organisation-links`
  MODIFY `linkID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

