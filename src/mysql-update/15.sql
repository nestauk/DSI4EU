SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


CREATE TABLE `project-post-comment-replies` (
  `id` int(10) UNSIGNED NOT NULL,
  `commentID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL,
  `comment` varchar(20000) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `project-post-comment-replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `postID` (`commentID`) USING BTREE;

ALTER TABLE `project-post-comment-replies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
  
