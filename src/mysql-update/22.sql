-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2016 at 11:10 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 7.0.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `dsi4eu`
--

-- --------------------------------------------------------

--
-- Table structure for table `organisation-member-invitations`
--

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
