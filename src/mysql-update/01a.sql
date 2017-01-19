-- 1.sql
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
  
  
-- 2.sql
ALTER TABLE `projects` ADD `url` VARCHAR(1024) NOT NULL AFTER `description`;

-- 3.sql
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

-- 4.sql
CREATE TABLE `project-member-requests` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `project-member-requests`
  ADD UNIQUE KEY `projectID` (`projectID`,`userID`);
  
  
-- 5.sql
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


-- 6.sql
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

-- 7.sql
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

-- 8.sql
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

-- 9.sql
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
  
  
-- 10.sql
ALTER TABLE `users` ADD `jobTitle` CHAR(255) NOT NULL AFTER `location`;


-- 11.sql
ALTER TABLE `organisations` ADD `partnersCount` INT UNSIGNED NOT NULL AFTER `organisationSizeID`;

-- 12.sql
CREATE TABLE `project-email-invitations` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `byUserID` int(10) UNSIGNED NOT NULL,
  `email` char(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `project-email-invitations`
  ADD UNIQUE KEY `projectID` (`projectID`,`email`);

-- 13.sql
ALTER TABLE `project-members` ADD `isAdmin` TINYINT(1) NOT NULL AFTER `userID`;

-- 14.sql
CREATE TABLE `project-post-comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `postID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL,
  `comment` varchar(20000) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `repliesCount` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `project-post-comments`
  ADD PRIMARY KEY (`id`),
  ADD INDEX `postID` (`postID`);

ALTER TABLE `project-post-comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

-- 15.sql
CREATE TABLE `project-post-comment-replies` (
  `id` int(10) UNSIGNED NOT NULL,
  `commentID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL,
  `comment` varchar(20000) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `project-post-comment-replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `postID` (`commentID`) USING BTREE;

ALTER TABLE `project-post-comment-replies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;


-- 16.sql
ALTER TABLE `project-posts` ADD `commentsCount` INT UNSIGNED NOT NULL AFTER `text`;


-- 17.sql
ALTER TABLE `organisations` ADD `importID` CHAR(255) NOT NULL AFTER `partnersCount`;
ALTER TABLE `projects` ADD `importID` CHAR(255) NOT NULL AFTER `organisationsCount`;

-- 18.sql
ALTER TABLE `organisations` ADD `projectsCount` INT UNSIGNED NOT NULL AFTER `organisationSizeID`;

-- 19.sql
CREATE TABLE `stories` (
  `id` int(10) UNSIGNED NOT NULL,
  `writerID` int(10) UNSIGNED NOT NULL,
  `title` varchar(2000) CHARACTER SET utf8 NOT NULL,
  `content` varchar(10000) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `stories`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `stories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `stories` ADD `time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `content`;


CREATE TABLE `story-categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` char(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `story-categories`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `story-categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `stories` ADD `categoryID` INT UNSIGNED NOT NULL AFTER `id`;
ALTER TABLE `stories` ADD `bgImage` CHAR(255) NOT NULL AFTER `content`;


-- 20.sql
ALTER TABLE `stories` ADD `featuredImage` CHAR(255) NOT NULL AFTER `content`;
ALTER TABLE `stories` ADD `isPublished` TINYINT(1) NOT NULL AFTER `time`;
ALTER TABLE `stories` ADD `datePublished` DATE NOT NULL AFTER `isPublished`;

-- 21.sql
CREATE TABLE `project-member-invitations` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `project-member-invitations`
  ADD UNIQUE KEY `projectID` (`projectID`,`userID`);

-- 22.sql
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

-- 23.sql
ALTER TABLE `users` ADD `showEmail` TINYINT(1) NOT NULL AFTER `email`;
ALTER TABLE `users` ADD `company` CHAR(255) NOT NULL AFTER `jobTitle`;

ALTER TABLE `users` ADD `cityName` CHAR(255) NOT NULL AFTER `location`, ADD `countryName` CHAR(255) NOT NULL AFTER `cityName`;


-- 24.sql
ALTER TABLE `projects` ADD `logo` CHAR(50) NOT NULL AFTER `importID`;

-- 25.sql
ALTER TABLE `organisation-types` ADD `order` TINYINT UNSIGNED NOT NULL AFTER `name`;
ALTER TABLE `organisation-sizes` ADD `order` TINYINT UNSIGNED NOT NULL AFTER `name`;

-- 26.sql
ALTER TABLE `users` ADD `isAdmin` TINYINT(1) NOT NULL AFTER `profilePic`, ADD `isSuperAdmin` TINYINT(1) NOT NULL AFTER `isAdmin`;

-- 27.sql
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

-- 28.sql
ALTER TABLE `users` ADD `isDisabled` TINYINT(1) NOT NULL AFTER `isSuperAdmin`;

ALTER TABLE `projects` ADD `shortDescription` VARCHAR(1000) NOT NULL AFTER `name`;

ALTER TABLE `projects` ADD `socialImpact` VARCHAR(10000) NOT NULL AFTER `logo`;

ALTER TABLE `projects` ADD `isPublished` TINYINT(1) NOT NULL AFTER `socialImpact`;

CREATE TABLE `case-studies` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` char(255) NOT NULL,
  `introCardText` varchar(10000) NOT NULL,
  `introPageText` varchar(10000) NOT NULL,
  `mainText` text NOT NULL,
  `projectStartDate` date NOT NULL,
  `projectEndDate` date NOT NULL,
  `url` char(255) NOT NULL,
  `buttonLabel` char(255) NOT NULL,
  `logo` char(50) NOT NULL,
  `cardImage` char(50) NOT NULL,
  `headerImage` char(50) NOT NULL,
  `cardColour` char(10) NOT NULL,
  `isPublished` tinyint(1) NOT NULL,
  `regionID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `case-studies`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `case-studies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

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

CREATE TABLE `project-links` (
  `linkID` int(10) UNSIGNED NOT NULL,
  `projectID` int(10) UNSIGNED NOT NULL,
  `link` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `project-links`
  ADD PRIMARY KEY (`linkID`);

ALTER TABLE `project-links`
  MODIFY `linkID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `projects` ADD `headerImage` CHAR(50) NOT NULL AFTER `logo`;

ALTER TABLE `case-studies` ADD `isFeaturedOnSlider` TINYINT(1) NOT NULL AFTER `isPublished`, ADD `isFeaturedOnHomePage` TINYINT(1) NOT NULL AFTER `isFeaturedOnSlider`;

-- --------------------------------------------------------

--
-- Table structure for table `dsi-focus-tags`
--

CREATE TABLE `dsi-focus-tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `tag` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dsi-focus-tags`
--

INSERT INTO `dsi-focus-tags` (`id`, `tag`) VALUES
(4, 'Open knowledge'),
(8, 'Open data'),
(9, 'Open networks'),
(35, 'Open hardware');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dsi-focus-tags`
--
ALTER TABLE `dsi-focus-tags`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dsi-focus-tags`
--
ALTER TABLE `dsi-focus-tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

ALTER TABLE `stories` CHANGE `content` `content` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `users` ADD `role` ENUM('user', 'sys-admin', 'community-admin', 'editorial-admin') NOT NULL AFTER `isDisabled`;

CREATE TABLE `translate` (
  `index` char(255) NOT NULL,
  `details` text NOT NULL,
  `en` text NOT NULL,
  `de` text NOT NULL,
  `fr` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `translate`
  ADD UNIQUE KEY `index` (`index`);


CREATE TABLE `funding-sources` (
  `id` int(11) NOT NULL,
  `title` char(255) NOT NULL,
  `url` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `funding-sources`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `funding-sources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

CREATE TABLE `fundings` (
 `id` INT NOT NULL AUTO_INCREMENT ,
 `title` CHAR(255) NOT NULL ,
 `url` VARCHAR(1024) NOT NULL ,
 `description` VARCHAR(10000) NOT NULL ,
 `closingDate` DATE NOT NULL ,
 `fundingSourceID` INT NOT NULL ,
 `countryID` INT NOT NULL ,
 PRIMARY KEY (`id`)
) ENGINE = InnoDB;

ALTER TABLE `fundings` ADD `timeCreated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `countryID`;

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` char(255) NOT NULL,
  `url` varchar(1024) NOT NULL,
  `shortDesc` varchar(5000) NOT NULL,
  `description` text NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `timeCreated` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `regionID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

CREATE TABLE `mails` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `mails`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `mails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `events` ADD `address` TEXT NOT NULL AFTER `endDate`, ADD `phoneNumber` CHAR(255) NOT NULL AFTER `address`, ADD `emailAddress` CHAR(255) NOT NULL AFTER `phoneNumber`, ADD `price` CHAR(50) NOT NULL AFTER `emailAddress`;

CREATE TABLE `app-registrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `loggedInUserID` int(10) UNSIGNED NOT NULL,
  `registeredUserID` int(10) UNSIGNED NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `app-registrations`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `app-registrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `case-studies` ADD `infoText` VARCHAR(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `introPageText`;
ALTER TABLE `case-studies` ADD `projectID` INT UNSIGNED NOT NULL AFTER `regionID`, ADD `organisationID` INT UNSIGNED NOT NULL AFTER `projectID`;

ALTER TABLE `fundings` ADD `typeID` INT UNSIGNED NOT NULL AFTER `countryID`;
ALTER TABLE `fundings` ADD `targets` CHAR(255) NOT NULL AFTER `typeID`;

ALTER TABLE `stories` ADD `cardShortDescription` CHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `title`;

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

ALTER TABLE `organisations` ADD `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `importID`;
ALTER TABLE `projects` ADD `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `importID`;

ALTER TABLE `organisation-follow` CHANGE `since` `since` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `project-follow` CHANGE `since` `since` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;

CREATE TABLE `network-tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `tag` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `network-tags`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `network-tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

CREATE TABLE `project-network-tags` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `tagID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `project-network-tags`
  ADD UNIQUE KEY `projectID` (`projectID`,`tagID`);

CREATE TABLE `organisation-network-tags` (
  `organisationID` int(10) UNSIGNED NOT NULL,
  `tagID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `organisation-network-tags`
  ADD UNIQUE KEY `projectID` (`organisationID`,`tagID`);


