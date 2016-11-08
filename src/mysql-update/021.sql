CREATE TABLE `organisation-follow` (
  `userID` int(10) UNSIGNED NOT NULL,
  `organisationID` int(10) UNSIGNED NOT NULL,
  `since` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `organisation-follow`
  ADD UNIQUE KEY `userID` (`userID`,`organisationID`);

CREATE TABLE `project-follow` (
  `userID` int(10) UNSIGNED NOT NULL,
  `projectID` int(10) UNSIGNED NOT NULL,
  `since` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `project-follow`
  ADD UNIQUE KEY `userID` (`userID`,`projectID`);
