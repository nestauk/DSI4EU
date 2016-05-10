CREATE TABLE `project-member-requests` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `project-member-requests`
  ADD UNIQUE KEY `projectID` (`projectID`,`userID`);