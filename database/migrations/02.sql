-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 05, 2017 at 12:37 PM
-- Server version: 10.1.22-MariaDB
-- PHP Version: 7.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `dsi4eu`
--

-- --------------------------------------------------------

--
-- Table structure for table `content-updates`
--

CREATE TABLE `content-updates` (
  `id` int(11) NOT NULL,
  `projectID` int(11) NOT NULL,
  `organisationID` int(11) NOT NULL,
  `updated` enum('new', 'title','content') NOT NULL DEFAULT 'new',
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `content-updates`
--
ALTER TABLE `content-updates`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `content-updates`
--
ALTER TABLE `content-updates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;COMMIT;
