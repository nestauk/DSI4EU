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