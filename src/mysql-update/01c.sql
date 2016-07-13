--- 21.sql
CREATE TABLE `project-member-invitations` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `project-member-invitations`
  ADD UNIQUE KEY `projectID` (`projectID`,`userID`);

--- 22.sql
CREATE TABLE `organisation-member-invitations` (
  `organisationID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `organisation-member-invitations`
--
ALTER TABLE `organisation-member-invitations`
  ADD UNIQUE KEY `organisationID` (`organisationID`,`userID`);

--- 23.sql
ALTER TABLE `users` ADD `showEmail` TINYINT(1) NOT NULL AFTER `email`;
ALTER TABLE `users` ADD `company` CHAR(255) NOT NULL AFTER `jobTitle`;

ALTER TABLE `users` ADD `cityName` CHAR(255) NOT NULL AFTER `location`, ADD `countryName` CHAR(255) NOT NULL AFTER `cityName`;


--- 24.sql
ALTER TABLE `projects` ADD `logo` CHAR(50) NOT NULL AFTER `importID`;

--- 25.sql
ALTER TABLE `organisation-types` ADD `order` TINYINT UNSIGNED NOT NULL AFTER `name`;
ALTER TABLE `organisation-sizes` ADD `order` TINYINT UNSIGNED NOT NULL AFTER `name`;

--- 26.sql
ALTER TABLE `users` ADD `isAdmin` TINYINT(1) NOT NULL AFTER `profilePic`, ADD `isSuperAdmin` TINYINT(1) NOT NULL AFTER `isAdmin`;

--- 27.sql
CREATE TABLE `report-profile` (
  `id` int(10) UNSIGNED NOT NULL,
  `byUserID` int(10) UNSIGNED NOT NULL,
  `reportedUserId` int(10) UNSIGNED NOT NULL,
  `comment` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reviewedByUserID` int(10) UNSIGNED NOT NULL,
  `reviewedTime` datetime NOT NULL,
  `review` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `report-profile`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `report-profile`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
  
--- 28.sql
ALTER TABLE `users` ADD `isDisabled` TINYINT(1) NOT NULL AFTER `isSuperAdmin`;