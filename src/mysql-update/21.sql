CREATE TABLE `project-member-invitations` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `project-member-invitations`
  ADD UNIQUE KEY `projectID` (`projectID`,`userID`);
