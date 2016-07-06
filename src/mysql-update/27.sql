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

ALTER TABLE `report-profile`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `report-profile`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;