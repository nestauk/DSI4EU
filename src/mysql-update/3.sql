ALTER TABLE `projects` ADD `status` ENUM('live','closed') NOT NULL AFTER `url`;
ALTER TABLE `projects` ADD `startDate` DATE NOT NULL AFTER `status`, ADD `endDate` DATE NOT NULL AFTER `startDate`;


CREATE TABLE `tags-for-projects` (
  `id` int(10) UNSIGNED NOT NULL,
  `tag` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `tags-for-projects`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `tags-for-projects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
  
  
  
CREATE TABLE `project-tags` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `tagID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `project-tags`
  ADD UNIQUE KEY `projectID` (`projectID`,`tagID`);

  
CREATE TABLE `impact-tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `tag` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `impact-tags`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `impact-tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
  
  
CREATE TABLE `project-impact-tags-a` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `tagID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `project-impact-tags-a`
  ADD UNIQUE KEY `projectID` (`projectID`,`tagID`);
  
CREATE TABLE `project-impact-tags-b` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `tagID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `project-impact-tags-b`
  ADD UNIQUE KEY `projectID` (`projectID`,`tagID`);
  
CREATE TABLE `project-impact-tags-c` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `tagID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `project-impact-tags-c`
  ADD UNIQUE KEY `projectID` (`projectID`,`tagID`);
