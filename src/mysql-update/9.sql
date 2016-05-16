CREATE TABLE `project-posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `projectID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` varchar(1000) NOT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `project-posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projectID` (`projectID`);

ALTER TABLE `project-posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
  

CREATE TABLE `password-recovery` (
  `id` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL,
  `code` char(10) NOT NULL,
  `expires` datetime NOT NULL,
  `isUsed` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `password-recovery`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `password-recovery`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;