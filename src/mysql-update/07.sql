CREATE TABLE `project-links` (
  `linkID` int(10) UNSIGNED NOT NULL,
  `projectID` int(10) UNSIGNED NOT NULL,
  `link` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `project-links`
  ADD PRIMARY KEY (`linkID`);

ALTER TABLE `project-links`
  MODIFY `linkID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
  
ALTER TABLE `projects` ADD `headerImage` CHAR(50) NOT NULL AFTER `logo`;