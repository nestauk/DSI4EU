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
  
