CREATE TABLE `project-post-comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `postID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL,
  `comment` varchar(20000) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `repliesCount` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `project-post-comments`
  ADD PRIMARY KEY (`id`),
  ADD INDEX KEY `postID` (`postID`);

ALTER TABLE `project-post-comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;