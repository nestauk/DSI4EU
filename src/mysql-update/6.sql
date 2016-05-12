SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `organisation-types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `organisation-types`
  ADD PRIMARY KEY (`id`);

  ALTER TABLE `organisation-types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
  
INSERT INTO `organisation-types` (`id`, `name`) VALUES
(1, 'Community Enterprise'),
(2, 'Social Firm'),
(3, 'Co-operative'),
(4, 'Credit Union'),
(5, 'Community Development Finance Institution'),
(6, 'Development Trust'),
(7, 'Public sector spin-out'),
(8, 'Trading arms of charity'),
(9, 'Fair Trade organisation'),
(10, 'Other type of social enterprise');  

ALTER TABLE `organisations` ADD `organisationTypeID` INT UNSIGNED NOT NULL AFTER `countryRegionID`;





CREATE TABLE `organisation-sizes` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `organisation-sizes`
  ADD PRIMARY KEY (`id`);

  ALTER TABLE `organisation-sizes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
  
INSERT INTO `organisation-sizes` (`id`, `name`) VALUES
(null, 'Under 10 staff members'),
(null, 'Between 10-20 staff members'),
(null, 'Between 20-50 staff members'),
(null, 'Between 50-100 staff members'),
(null, 'Between 100-500 staff members'),
(null, 'More than 500 staff members');

ALTER TABLE `organisations` ADD `organisationSizeID` INT UNSIGNED NOT NULL AFTER `organisationTypeID`;