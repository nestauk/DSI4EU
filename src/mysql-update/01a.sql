---1.sql
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `dsi4eu-test`
--

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `language` char(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `organisation-members`
--

DROP TABLE IF EXISTS `organisation-members`;
CREATE TABLE `organisation-members` (
  `organisationID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL,
  `isAdmin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `organisations`
--

DROP TABLE IF EXISTS `organisations`;
CREATE TABLE `organisations` (
  `id` int(10) UNSIGNED NOT NULL,
  `ownerID` int(10) UNSIGNED NOT NULL,
  `name` char(255) NOT NULL,
  `description` varchar(20000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `project-members`
--

DROP TABLE IF EXISTS `project-members`;
CREATE TABLE `project-members` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
  `id` int(10) UNSIGNED NOT NULL,
  `ownerID` int(10) UNSIGNED NOT NULL,
  `name` char(255) NOT NULL,
  `description` varchar(20000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

DROP TABLE IF EXISTS `skills`;
CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `skill` char(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user-languages`
--

DROP TABLE IF EXISTS `user-languages`;
CREATE TABLE `user-languages` (
  `userID` int(10) UNSIGNED NOT NULL,
  `langID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user-links`
--

DROP TABLE IF EXISTS `user-links`;
CREATE TABLE `user-links` (
  `linkID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL,
  `link` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user-skills`
--

DROP TABLE IF EXISTS `user-skills`;
CREATE TABLE `user-skills` (
  `userID` int(10) UNSIGNED NOT NULL,
  `skillID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `password` char(255) NOT NULL,
  `email` char(255) NOT NULL,
  `fname` char(255) CHARACTER SET utf8 NOT NULL,
  `lname` char(255) CHARACTER SET utf8 NOT NULL,
  `bio` varchar(20000) CHARACTER SET utf8 NOT NULL,
  `location` char(255) NOT NULL,
  `facebookUID` char(255) NOT NULL,
  `googleUID` char(255) NOT NULL,
  `gitHubUID` char(255) NOT NULL,
  `twitterUID` char(255) NOT NULL,
  `profileURL` char(255) NOT NULL,
  `profilePic` char(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `language` (`language`) USING BTREE;

--
-- Indexes for table `organisation-members`
--
ALTER TABLE `organisation-members`
  ADD UNIQUE KEY `organisationID` (`organisationID`,`userID`);

--
-- Indexes for table `organisations`
--
ALTER TABLE `organisations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project-members`
--
ALTER TABLE `project-members`
  ADD UNIQUE KEY `projectID` (`projectID`,`userID`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `skill` (`skill`);

--
-- Indexes for table `user-languages`
--
ALTER TABLE `user-languages`
  ADD UNIQUE KEY `userID_2` (`userID`,`langID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `langID` (`langID`) USING BTREE;

--
-- Indexes for table `user-links`
--
ALTER TABLE `user-links`
  ADD PRIMARY KEY (`linkID`);

--
-- Indexes for table `user-skills`
--
ALTER TABLE `user-skills`
  ADD UNIQUE KEY `userID_2` (`userID`,`skillID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `skillID` (`skillID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profileURL` (`profileURL`),
  ADD KEY `facebookUID` (`facebookUID`),
  ADD KEY `email` (`email`),
  ADD KEY `googleUID` (`googleUID`),
  ADD KEY `gitHubUID` (`gitHubUID`),
  ADD KEY `twitterUID` (`twitterUID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `organisations`
--
ALTER TABLE `organisations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user-links`
--
ALTER TABLE `user-links`
  MODIFY `linkID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
  
  
--- 2.sql
ALTER TABLE `projects` ADD `url` VARCHAR(1024) NOT NULL AFTER `description`;

--- 3.sql
ALTER TABLE `projects` ADD `status` ENUM('live','closed') NOT NULL AFTER `url`;
ALTER TABLE `projects` ADD `startDate` DATE NOT NULL AFTER `status`, ADD `endDate` DATE NOT NULL AFTER `startDate`;


CREATE TABLE `tags-for-projects` (
  `id` int(10) UNSIGNED NOT NULL,
  `tag` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `tags-for-projects`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `tags-for-projects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
  
  
  
CREATE TABLE `project-tags` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `tagID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `project-tags`
  ADD UNIQUE KEY `projectID` (`projectID`,`tagID`);

  
CREATE TABLE `impact-tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `tag` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `impact-tags`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `impact-tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
  
  
CREATE TABLE `project-impact-tags-a` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `tagID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `project-impact-tags-a`
  ADD UNIQUE KEY `projectID` (`projectID`,`tagID`);
  
CREATE TABLE `project-impact-tags-b` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `tagID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `project-impact-tags-b`
  ADD UNIQUE KEY `projectID` (`projectID`,`tagID`);
  
CREATE TABLE `project-impact-tags-c` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `tagID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `project-impact-tags-c`
  ADD UNIQUE KEY `projectID` (`projectID`,`tagID`);

ALTER TABLE `projects` ADD `status` ENUM('live','closed') NOT NULL AFTER `url`;
ALTER TABLE `projects` ADD `startDate` DATE NOT NULL AFTER `status`, ADD `endDate` DATE NOT NULL AFTER `startDate`;


CREATE TABLE `tags-for-projects` (
  `id` int(10) UNSIGNED NOT NULL,
  `tag` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `tags-for-projects`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `tags-for-projects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
  
  
  
CREATE TABLE `project-tags` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `tagID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `project-tags`
  ADD UNIQUE KEY `projectID` (`projectID`,`tagID`);

  
CREATE TABLE `impact-tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `tag` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `impact-tags`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `impact-tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
  
  
CREATE TABLE `project-impact-tags-a` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `tagID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `project-impact-tags-a`
  ADD UNIQUE KEY `projectID` (`projectID`,`tagID`);
  
CREATE TABLE `project-impact-tags-b` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `tagID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `project-impact-tags-b`
  ADD UNIQUE KEY `projectID` (`projectID`,`tagID`);
  
CREATE TABLE `project-impact-tags-c` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `tagID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `project-impact-tags-c`
  ADD UNIQUE KEY `projectID` (`projectID`,`tagID`);

--- 4.sql
CREATE TABLE `project-member-requests` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `project-member-requests`
  ADD UNIQUE KEY `projectID` (`projectID`,`userID`);
  
  
--- 5.sql
CREATE TABLE `countries` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `countries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
  
  

CREATE TABLE `country-regions` (
  `id` int(10) UNSIGNED NOT NULL,
  `countryID` int(10) UNSIGNED NOT NULL,
  `name` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `country-regions`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `country-regions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
  
ALTER TABLE `projects` ADD `countryID` INT UNSIGNED NOT NULL AFTER `endDate`, ADD `countryRegionID` INT UNSIGNED NOT NULL AFTER `countryID`, ADD INDEX (`countryID`), ADD INDEX (`countryRegionID`);
ALTER TABLE `organisations` ADD `countryID` INT UNSIGNED NOT NULL AFTER `description`, ADD `countryRegionID` INT UNSIGNED NOT NULL AFTER `countryID`, ADD INDEX (`countryID`), ADD INDEX (`countryRegionID`);


--- 6.sql
CREATE TABLE `organisation-types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `organisation-types`
  ADD PRIMARY KEY (`id`);

  ALTER TABLE `organisation-types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
  
ALTER TABLE `organisations` ADD `organisationTypeID` INT UNSIGNED NOT NULL AFTER `countryRegionID`;

CREATE TABLE `organisation-sizes` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `organisation-sizes`
  ADD PRIMARY KEY (`id`);

  ALTER TABLE `organisation-sizes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
  
ALTER TABLE `organisations` ADD `organisationSizeID` INT UNSIGNED NOT NULL AFTER `organisationTypeID`;

--- 7.sql
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

--- 8.sql
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

--- 9.sql
CREATE TABLE `project-posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `projectID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` varchar(1000) NOT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `project-posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projectID` (`projectID`);

ALTER TABLE `project-posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
  

CREATE TABLE `password-recovery` (
  `id` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL,
  `code` char(10) NOT NULL,
  `expires` datetime NOT NULL,
  `isUsed` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `password-recovery`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `password-recovery`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
  
  
--- 10.sql
ALTER TABLE `users` ADD `jobTitle` CHAR(255) NOT NULL AFTER `location`;