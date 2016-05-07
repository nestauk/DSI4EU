-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2016 at 03:57 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 7.0.5

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