-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 26, 2016 at 03:47 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 7.0.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `dsi4eu`
--

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
(4, 'Open knowledge\r\n'),
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