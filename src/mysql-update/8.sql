CREATE TABLE `organisation-member-requests` (
  `organisationID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `organisation-member-requests`
  ADD UNIQUE KEY `organisationID` (`organisationID`,`userID`);
  

CREATE TABLE `organisation-projects` (
  `organisationID` int(10) UNSIGNED NOT NULL,
  `projectID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `organisation-projects`
  ADD UNIQUE KEY `projectID` (`projectID`,`organisationID`);
  
ALTER TABLE `projects` ADD `organisationsCount` INT UNSIGNED NOT NULL AFTER `countryRegionID`;