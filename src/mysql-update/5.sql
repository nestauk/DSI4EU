SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `countries` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `countries` (`id`, `name`) VALUES
(1, 'Albania'),
(2, 'Andorra'),
(3, 'Armenia'),
(4, 'Austria'),
(5, 'Azerbaijan'),
(6, 'Belarus'),
(7, 'Belgium'),
(8, 'Bosnia and Herzegovina'),
(9, 'Bulgaria'),
(10, 'Croatia'),
(11, 'Cyprus'),
(12, 'Czech Republic'),
(13, 'Denmark'),
(14, 'Estonia'),
(15, 'Finland'),
(16, 'France'),
(17, 'Georgia'),
(18, 'Germany'),
(19, 'Greece'),
(20, 'Hungary'),
(21, 'Iceland'),
(22, 'Ireland'),
(23, 'Italy'),
(24, 'Kazakhstan'),
(25, 'Kosovo'),
(26, 'Latvia'),
(27, 'Liechtenstein'),
(28, 'Lithuania'),
(29, 'Luxembourg'),
(30, 'Macedonia'),
(31, 'Malta'),
(32, 'Moldova'),
(33, 'Monaco'),
(34, 'Montenegro'),
(35, 'Netherlands'),
(36, 'Norway'),
(37, 'Poland'),
(38, 'Portugal'),
(39, 'Romania'),
(40, 'Russia'),
(41, 'San Marino'),
(42, 'Serbia'),
(43, 'Slovakia'),
(44, 'Slovenia'),
(45, 'Spain'),
(46, 'Sweden'),
(47, 'Switzerland'),
(48, 'Turkey'),
(49, 'Ukraine'),
(50, 'United Kingdom (UK)'),
(51, 'Vatican City (Holy See)');

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
