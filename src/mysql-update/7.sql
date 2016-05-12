CREATE TABLE `tags-for-organisations` (
  `id` int(10) UNSIGNED NOT NULL,
  `tag` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `tags-for-organisations`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `tags-for-organisations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
  
  
CREATE TABLE `organisation-tags` (
  `organisationID` int(10) UNSIGNED NOT NULL,
  `tagID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `organisation-tags`
  ADD UNIQUE KEY `organisationID` (`organisationID`,`tagID`);

ALTER TABLE `organisations` ADD `address` VARCHAR(2000) NOT NULL AFTER `countryRegionID`;