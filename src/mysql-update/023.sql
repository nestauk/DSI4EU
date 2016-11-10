CREATE TABLE `network-tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `tag` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `network-tags`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `network-tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
  
CREATE TABLE `project-network-tags` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `tagID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `project-network-tags`
  ADD UNIQUE KEY `projectID` (`projectID`,`tagID`);

CREATE TABLE `organisation-network-tags` (
  `organisationID` int(10) UNSIGNED NOT NULL,
  `tagID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `organisation-network-tags`
  ADD UNIQUE KEY `projectID` (`organisationID`,`tagID`);

