-- phpMyAdmin SQL Dump
-- version 4.6.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 25, 2017 at 04:21 PM
-- Server version: 5.5.46
-- PHP Version: 7.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dsi4eu`
--

-- --------------------------------------------------------

--
-- Table structure for table `app-registrations`
--

DROP TABLE IF EXISTS `app-registrations`;
CREATE TABLE `app-registrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `loggedInUserID` int(10) UNSIGNED NOT NULL,
  `registeredUserID` int(10) UNSIGNED NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `auth-tokens`
--

DROP TABLE IF EXISTS `auth-tokens`;
CREATE TABLE `auth-tokens` (
  `selector` char(32) NOT NULL,
  `token` char(255) NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL,
  `ip` char(32) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastUse` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `case-studies`
--

DROP TABLE IF EXISTS `case-studies`;
CREATE TABLE `case-studies` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` char(255) NOT NULL,
  `introCardText` varchar(10000) NOT NULL,
  `introPageText` varchar(10000) NOT NULL,
  `infoText` varchar(1024) CHARACTER SET utf8 NOT NULL,
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
  `isFeaturedOnSlider` tinyint(1) NOT NULL,
  `isFeaturedOnHomePage` tinyint(1) NOT NULL,
  `regionID` int(11) NOT NULL,
  `projectID` int(10) UNSIGNED NOT NULL,
  `organisationID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `country-regions`
--

DROP TABLE IF EXISTS `country-regions`;
CREATE TABLE `country-regions` (
  `id` int(10) UNSIGNED NOT NULL,
  `countryID` int(10) UNSIGNED NOT NULL,
  `name` char(255) NOT NULL,
  `lat` decimal(10,7) NOT NULL,
  `lng` decimal(10,7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dsi-focus-tags`
--

DROP TABLE IF EXISTS `dsi-focus-tags`;
CREATE TABLE `dsi-focus-tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `tag` char(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` char(255) NOT NULL,
  `url` varchar(1024) NOT NULL,
  `shortDesc` varchar(5000) NOT NULL,
  `description` text NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `address` text NOT NULL,
  `phoneNumber` char(255) NOT NULL,
  `emailAddress` char(255) NOT NULL,
  `price` char(50) NOT NULL,
  `timeCreated` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `regionID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `funding-sources`
--

DROP TABLE IF EXISTS `funding-sources`;
CREATE TABLE `funding-sources` (
  `id` int(11) NOT NULL,
  `title` char(255) NOT NULL,
  `url` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `fundings`
--

DROP TABLE IF EXISTS `fundings`;
CREATE TABLE `fundings` (
  `id` int(11) NOT NULL,
  `title` char(255) NOT NULL,
  `url` varchar(1024) NOT NULL,
  `description` varchar(10000) NOT NULL,
  `closingDate` date NOT NULL,
  `fundingSourceID` int(11) NOT NULL,
  `countryID` int(11) NOT NULL,
  `typeID` int(10) UNSIGNED NOT NULL,
  `targets` char(255) NOT NULL,
  `timeCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `impact-tags`
--

DROP TABLE IF EXISTS `impact-tags`;
CREATE TABLE `impact-tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `tag` char(255) NOT NULL,
  `isMain` tinyint(1) UNSIGNED NOT NULL,
  `order` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
-- Table structure for table `mails`
--

DROP TABLE IF EXISTS `mails`;
CREATE TABLE `mails` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `network-tags`
--

DROP TABLE IF EXISTS `network-tags`;
CREATE TABLE `network-tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `tag` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `organisation-follow`
--

DROP TABLE IF EXISTS `organisation-follow`;
CREATE TABLE `organisation-follow` (
  `userID` int(10) UNSIGNED NOT NULL,
  `organisationID` int(10) UNSIGNED NOT NULL,
  `since` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `organisation-links`
--

DROP TABLE IF EXISTS `organisation-links`;
CREATE TABLE `organisation-links` (
  `linkID` int(10) UNSIGNED NOT NULL,
  `organisationID` int(10) UNSIGNED NOT NULL,
  `link` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `organisation-member-invitations`
--

DROP TABLE IF EXISTS `organisation-member-invitations`;
CREATE TABLE `organisation-member-invitations` (
  `organisationID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `organisation-member-requests`
--

DROP TABLE IF EXISTS `organisation-member-requests`;
CREATE TABLE `organisation-member-requests` (
  `organisationID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL
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
-- Table structure for table `organisation-network-tags`
--

DROP TABLE IF EXISTS `organisation-network-tags`;
CREATE TABLE `organisation-network-tags` (
  `organisationID` int(10) UNSIGNED NOT NULL,
  `tagID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `organisation-projects`
--

DROP TABLE IF EXISTS `organisation-projects`;
CREATE TABLE `organisation-projects` (
  `organisationID` int(10) UNSIGNED NOT NULL,
  `projectID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `organisation-sizes`
--

DROP TABLE IF EXISTS `organisation-sizes`;
CREATE TABLE `organisation-sizes` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` char(255) NOT NULL,
  `order` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `organisation-tags`
--

DROP TABLE IF EXISTS `organisation-tags`;
CREATE TABLE `organisation-tags` (
  `organisationID` int(10) UNSIGNED NOT NULL,
  `tagID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `organisation-types`
--

DROP TABLE IF EXISTS `organisation-types`;
CREATE TABLE `organisation-types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` char(255) NOT NULL,
  `order` tinyint(3) UNSIGNED NOT NULL
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
  `url` varchar(1000) NOT NULL,
  `shortDescription` varchar(10000) NOT NULL,
  `description` varchar(20000) NOT NULL,
  `countryID` int(10) UNSIGNED NOT NULL,
  `countryRegionID` int(10) UNSIGNED NOT NULL,
  `address` varchar(2000) NOT NULL,
  `organisationTypeID` int(10) UNSIGNED NOT NULL,
  `organisationSizeID` int(10) UNSIGNED NOT NULL,
  `startDate` date NOT NULL,
  `logo` char(255) NOT NULL,
  `headerImage` char(255) NOT NULL,
  `isPublished` tinyint(1) NOT NULL,
  `projectsCount` int(10) UNSIGNED NOT NULL,
  `partnersCount` int(10) UNSIGNED NOT NULL,
  `importID` char(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `password-recovery`
--

DROP TABLE IF EXISTS `password-recovery`;
CREATE TABLE `password-recovery` (
  `id` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL,
  `code` char(10) NOT NULL,
  `expires` datetime NOT NULL,
  `isUsed` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `project-email-invitations`
--

DROP TABLE IF EXISTS `project-email-invitations`;
CREATE TABLE `project-email-invitations` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `byUserID` int(10) UNSIGNED NOT NULL,
  `email` char(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `project-follow`
--

DROP TABLE IF EXISTS `project-follow`;
CREATE TABLE `project-follow` (
  `userID` int(10) UNSIGNED NOT NULL,
  `projectID` int(10) UNSIGNED NOT NULL,
  `since` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `project-impact-tags-a`
--

DROP TABLE IF EXISTS `project-impact-tags-a`;
CREATE TABLE `project-impact-tags-a` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `tagID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `project-impact-tags-b`
--

DROP TABLE IF EXISTS `project-impact-tags-b`;
CREATE TABLE `project-impact-tags-b` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `tagID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `project-impact-tags-c`
--

DROP TABLE IF EXISTS `project-impact-tags-c`;
CREATE TABLE `project-impact-tags-c` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `tagID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `project-links`
--

DROP TABLE IF EXISTS `project-links`;
CREATE TABLE `project-links` (
  `linkID` int(10) UNSIGNED NOT NULL,
  `projectID` int(10) UNSIGNED NOT NULL,
  `link` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `project-member-invitations`
--

DROP TABLE IF EXISTS `project-member-invitations`;
CREATE TABLE `project-member-invitations` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `project-member-requests`
--

DROP TABLE IF EXISTS `project-member-requests`;
CREATE TABLE `project-member-requests` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `project-members`
--

DROP TABLE IF EXISTS `project-members`;
CREATE TABLE `project-members` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL,
  `isAdmin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `project-network-tags`
--

DROP TABLE IF EXISTS `project-network-tags`;
CREATE TABLE `project-network-tags` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `tagID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `project-post-comment-replies`
--

DROP TABLE IF EXISTS `project-post-comment-replies`;
CREATE TABLE `project-post-comment-replies` (
  `id` int(10) UNSIGNED NOT NULL,
  `commentID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL,
  `comment` varchar(20000) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `project-post-comments`
--

DROP TABLE IF EXISTS `project-post-comments`;
CREATE TABLE `project-post-comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `postID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL,
  `comment` varchar(20000) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `repliesCount` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `project-posts`
--

DROP TABLE IF EXISTS `project-posts`;
CREATE TABLE `project-posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `projectID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` varchar(1000) NOT NULL,
  `text` text NOT NULL,
  `commentsCount` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `project-tags`
--

DROP TABLE IF EXISTS `project-tags`;
CREATE TABLE `project-tags` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `tagID` int(10) UNSIGNED NOT NULL
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
  `shortDescription` varchar(1000) NOT NULL,
  `description` varchar(20000) NOT NULL,
  `url` varchar(1024) NOT NULL,
  `status` enum('live','closed') NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `countryID` int(10) UNSIGNED NOT NULL,
  `countryRegionID` int(10) UNSIGNED NOT NULL,
  `organisationsCount` int(10) UNSIGNED NOT NULL,
  `importID` char(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastUpdate` date NOT NULL,
  `logo` char(50) NOT NULL,
  `headerImage` char(50) NOT NULL,
  `socialImpact` varchar(10000) NOT NULL,
  `isPublished` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `report-profile`
--

DROP TABLE IF EXISTS `report-profile`;
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
-- Table structure for table `stories`
--

DROP TABLE IF EXISTS `stories`;
CREATE TABLE `stories` (
  `id` int(10) UNSIGNED NOT NULL,
  `categoryID` int(10) UNSIGNED NOT NULL,
  `writerID` int(10) UNSIGNED NOT NULL,
  `title` varchar(2000) CHARACTER SET utf8 NOT NULL,
  `cardShortDescription` char(150) CHARACTER SET utf8 NOT NULL,
  `content` text CHARACTER SET utf8 NOT NULL,
  `featuredImage` char(255) NOT NULL,
  `bgImage` char(255) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isPublished` tinyint(1) NOT NULL,
  `datePublished` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `story-categories`
--

DROP TABLE IF EXISTS `story-categories`;
CREATE TABLE `story-categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` char(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tags-for-organisations`
--

DROP TABLE IF EXISTS `tags-for-organisations`;
CREATE TABLE `tags-for-organisations` (
  `id` int(10) UNSIGNED NOT NULL,
  `tag` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tags-for-projects`
--

DROP TABLE IF EXISTS `tags-for-projects`;
CREATE TABLE `tags-for-projects` (
  `id` int(10) UNSIGNED NOT NULL,
  `tag` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `terminate-account-tokens`
--

DROP TABLE IF EXISTS `terminate-account-tokens`;
CREATE TABLE `terminate-account-tokens` (
  `userID` int(10) UNSIGNED NOT NULL,
  `token` char(255) NOT NULL,
  `expires` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `translate`
--

DROP TABLE IF EXISTS `translate`;
CREATE TABLE `translate` (
  `index` char(255) NOT NULL,
  `details` text NOT NULL,
  `en` text NOT NULL,
  `de` text NOT NULL,
  `fr` text NOT NULL,
  `it` text NOT NULL,
  `es` text NOT NULL,
  `ca` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `showEmail` tinyint(1) NOT NULL,
  `fname` char(255) CHARACTER SET utf8 NOT NULL,
  `lname` char(255) CHARACTER SET utf8 NOT NULL,
  `bio` varchar(20000) CHARACTER SET utf8 NOT NULL,
  `location` char(255) NOT NULL,
  `cityName` char(255) NOT NULL,
  `countryName` char(255) NOT NULL,
  `jobTitle` char(255) NOT NULL,
  `company` char(255) NOT NULL,
  `facebookUID` char(255) NOT NULL,
  `googleUID` char(255) NOT NULL,
  `gitHubUID` char(255) NOT NULL,
  `twitterUID` char(255) NOT NULL,
  `profileURL` char(255) NOT NULL,
  `profilePic` char(100) NOT NULL,
  `isAdmin` tinyint(1) NOT NULL,
  `isSuperAdmin` tinyint(1) NOT NULL,
  `isDisabled` tinyint(1) NOT NULL,
  `role` enum('user','sys-admin','community-admin','editorial-admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `app-registrations`
--
ALTER TABLE `app-registrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth-tokens`
--
ALTER TABLE `auth-tokens`
  ADD PRIMARY KEY (`selector`);

--
-- Indexes for table `case-studies`
--
ALTER TABLE `case-studies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `country-regions`
--
ALTER TABLE `country-regions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dsi-focus-tags`
--
ALTER TABLE `dsi-focus-tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `funding-sources`
--
ALTER TABLE `funding-sources`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fundings`
--
ALTER TABLE `fundings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `impact-tags`
--
ALTER TABLE `impact-tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `language` (`language`) USING BTREE;

--
-- Indexes for table `mails`
--
ALTER TABLE `mails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `network-tags`
--
ALTER TABLE `network-tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organisation-follow`
--
ALTER TABLE `organisation-follow`
  ADD UNIQUE KEY `userID` (`userID`,`organisationID`);

--
-- Indexes for table `organisation-links`
--
ALTER TABLE `organisation-links`
  ADD PRIMARY KEY (`linkID`);

--
-- Indexes for table `organisation-member-invitations`
--
ALTER TABLE `organisation-member-invitations`
  ADD UNIQUE KEY `organisationID` (`organisationID`,`userID`);

--
-- Indexes for table `organisation-member-requests`
--
ALTER TABLE `organisation-member-requests`
  ADD UNIQUE KEY `organisationID` (`organisationID`,`userID`);

--
-- Indexes for table `organisation-members`
--
ALTER TABLE `organisation-members`
  ADD UNIQUE KEY `organisationID` (`organisationID`,`userID`);

--
-- Indexes for table `organisation-network-tags`
--
ALTER TABLE `organisation-network-tags`
  ADD UNIQUE KEY `projectID` (`organisationID`,`tagID`);

--
-- Indexes for table `organisation-projects`
--
ALTER TABLE `organisation-projects`
  ADD UNIQUE KEY `projectID` (`projectID`,`organisationID`);

--
-- Indexes for table `organisation-sizes`
--
ALTER TABLE `organisation-sizes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organisation-tags`
--
ALTER TABLE `organisation-tags`
  ADD UNIQUE KEY `organisationID` (`organisationID`,`tagID`);

--
-- Indexes for table `organisation-types`
--
ALTER TABLE `organisation-types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organisations`
--
ALTER TABLE `organisations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `countryID` (`countryID`),
  ADD KEY `countryRegionID` (`countryRegionID`);

--
-- Indexes for table `password-recovery`
--
ALTER TABLE `password-recovery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project-email-invitations`
--
ALTER TABLE `project-email-invitations`
  ADD UNIQUE KEY `projectID` (`projectID`,`email`);

--
-- Indexes for table `project-follow`
--
ALTER TABLE `project-follow`
  ADD UNIQUE KEY `userID` (`userID`,`projectID`);

--
-- Indexes for table `project-impact-tags-a`
--
ALTER TABLE `project-impact-tags-a`
  ADD UNIQUE KEY `projectID` (`projectID`,`tagID`);

--
-- Indexes for table `project-impact-tags-b`
--
ALTER TABLE `project-impact-tags-b`
  ADD UNIQUE KEY `projectID` (`projectID`,`tagID`);

--
-- Indexes for table `project-impact-tags-c`
--
ALTER TABLE `project-impact-tags-c`
  ADD UNIQUE KEY `projectID` (`projectID`,`tagID`);

--
-- Indexes for table `project-links`
--
ALTER TABLE `project-links`
  ADD PRIMARY KEY (`linkID`);

--
-- Indexes for table `project-member-invitations`
--
ALTER TABLE `project-member-invitations`
  ADD UNIQUE KEY `projectID` (`projectID`,`userID`);

--
-- Indexes for table `project-member-requests`
--
ALTER TABLE `project-member-requests`
  ADD UNIQUE KEY `projectID` (`projectID`,`userID`);

--
-- Indexes for table `project-members`
--
ALTER TABLE `project-members`
  ADD UNIQUE KEY `projectID` (`projectID`,`userID`);

--
-- Indexes for table `project-network-tags`
--
ALTER TABLE `project-network-tags`
  ADD UNIQUE KEY `projectID` (`projectID`,`tagID`);

--
-- Indexes for table `project-post-comment-replies`
--
ALTER TABLE `project-post-comment-replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `postID` (`commentID`) USING BTREE;

--
-- Indexes for table `project-post-comments`
--
ALTER TABLE `project-post-comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `postID` (`postID`);

--
-- Indexes for table `project-posts`
--
ALTER TABLE `project-posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projectID` (`projectID`);

--
-- Indexes for table `project-tags`
--
ALTER TABLE `project-tags`
  ADD UNIQUE KEY `projectID` (`projectID`,`tagID`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `countryID` (`countryID`),
  ADD KEY `countryRegionID` (`countryRegionID`);

--
-- Indexes for table `report-profile`
--
ALTER TABLE `report-profile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `skill` (`skill`);

--
-- Indexes for table `stories`
--
ALTER TABLE `stories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `story-categories`
--
ALTER TABLE `story-categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags-for-organisations`
--
ALTER TABLE `tags-for-organisations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags-for-projects`
--
ALTER TABLE `tags-for-projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `terminate-account-tokens`
--
ALTER TABLE `terminate-account-tokens`
  ADD UNIQUE KEY `token` (`token`);

--
-- Indexes for table `translate`
--
ALTER TABLE `translate`
  ADD UNIQUE KEY `index` (`index`);

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
-- AUTO_INCREMENT for table `app-registrations`
--
ALTER TABLE `app-registrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `case-studies`
--
ALTER TABLE `case-studies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;
--
-- AUTO_INCREMENT for table `country-regions`
--
ALTER TABLE `country-regions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=529;
--
-- AUTO_INCREMENT for table `dsi-focus-tags`
--
ALTER TABLE `dsi-focus-tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;
--
-- AUTO_INCREMENT for table `funding-sources`
--
ALTER TABLE `funding-sources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `fundings`
--
ALTER TABLE `fundings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;
--
-- AUTO_INCREMENT for table `impact-tags`
--
ALTER TABLE `impact-tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=988;
--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `mails`
--
ALTER TABLE `mails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=190;
--
-- AUTO_INCREMENT for table `network-tags`
--
ALTER TABLE `network-tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
--
-- AUTO_INCREMENT for table `organisation-links`
--
ALTER TABLE `organisation-links`
  MODIFY `linkID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=352;
--
-- AUTO_INCREMENT for table `organisation-sizes`
--
ALTER TABLE `organisation-sizes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `organisation-types`
--
ALTER TABLE `organisation-types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `organisations`
--
ALTER TABLE `organisations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1318;
--
-- AUTO_INCREMENT for table `password-recovery`
--
ALTER TABLE `password-recovery`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;
--
-- AUTO_INCREMENT for table `project-links`
--
ALTER TABLE `project-links`
  MODIFY `linkID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;
--
-- AUTO_INCREMENT for table `project-post-comment-replies`
--
ALTER TABLE `project-post-comment-replies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `project-post-comments`
--
ALTER TABLE `project-post-comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `project-posts`
--
ALTER TABLE `project-posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=924;
--
-- AUTO_INCREMENT for table `report-profile`
--
ALTER TABLE `report-profile`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250;
--
-- AUTO_INCREMENT for table `stories`
--
ALTER TABLE `stories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;
--
-- AUTO_INCREMENT for table `story-categories`
--
ALTER TABLE `story-categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tags-for-organisations`
--
ALTER TABLE `tags-for-organisations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=242;
--
-- AUTO_INCREMENT for table `tags-for-projects`
--
ALTER TABLE `tags-for-projects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=244;
--
-- AUTO_INCREMENT for table `user-links`
--
ALTER TABLE `user-links`
  MODIFY `linkID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=893;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2239;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
