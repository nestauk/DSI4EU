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
  
