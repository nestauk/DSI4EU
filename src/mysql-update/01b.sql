--- 11.sql
ALTER TABLE `organisations` ADD `partnersCount` INT UNSIGNED NOT NULL AFTER `organisationSizeID`;

--- 12.sql
CREATE TABLE `project-email-invitations` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `byUserID` int(10) UNSIGNED NOT NULL,
  `email` char(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `project-email-invitations`
  ADD UNIQUE KEY `projectID` (`projectID`,`email`);

--- 13.sql
ALTER TABLE `project-members` ADD `isAdmin` TINYINT(1) NOT NULL AFTER `userID`;

--- 14.sql
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
  ADD INDEX `postID` (`postID`);

ALTER TABLE `project-post-comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
  
--- 15.sql
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
  
  
--- 16.sql
ALTER TABLE `project-posts` ADD `commentsCount` INT UNSIGNED NOT NULL AFTER `text`;


--- 17.sql
ALTER TABLE `organisations` ADD `importID` CHAR(255) NOT NULL AFTER `partnersCount`;
ALTER TABLE `projects` ADD `importID` CHAR(255) NOT NULL AFTER `organisationsCount`;

--- 18.sql
ALTER TABLE `organisations` ADD `projectsCount` INT UNSIGNED NOT NULL AFTER `organisationSizeID`;

--- 19.sql
CREATE TABLE `stories` (
  `id` int(10) UNSIGNED NOT NULL,
  `writerID` int(10) UNSIGNED NOT NULL,
  `title` varchar(2000) CHARACTER SET utf8 NOT NULL,
  `content` varchar(10000) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `stories`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `stories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `stories` ADD `time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `content`;


CREATE TABLE `story-categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` char(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `story-categories`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `story-categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
  
ALTER TABLE `stories` ADD `categoryID` INT UNSIGNED NOT NULL AFTER `id`;
ALTER TABLE `stories` ADD `bgImage` CHAR(255) NOT NULL AFTER `content`;


--- 20.sql
ALTER TABLE `stories` ADD `featuredImage` CHAR(255) NOT NULL AFTER `content`;
ALTER TABLE `stories` ADD `isPublished` TINYINT(1) NOT NULL AFTER `time`;
ALTER TABLE `stories` ADD `datePublished` DATE NOT NULL AFTER `isPublished`;