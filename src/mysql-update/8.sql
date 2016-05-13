CREATE TABLE `organisation-member-requests` (
  `organisationID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `organisation-member-requests`
  ADD UNIQUE KEY `organisationID` (`organisationID`,`userID`);