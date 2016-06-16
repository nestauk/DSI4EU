ALTER TABLE `users` ADD `showEmail` TINYINT(1) NOT NULL AFTER `email`;
ALTER TABLE `users` ADD `company` CHAR(255) NOT NULL AFTER `jobTitle`;

ALTER TABLE `users` ADD `cityName` CHAR(255) NOT NULL AFTER `location`, ADD `countryName` CHAR(255) NOT NULL AFTER `cityName`;