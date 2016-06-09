ALTER TABLE `stories` ADD `featuredImage` CHAR(255) NOT NULL AFTER `content`;
ALTER TABLE `stories` ADD `isPublished` TINYINT(1) NOT NULL AFTER `time`;
ALTER TABLE `stories` ADD `datePublished` DATE NOT NULL AFTER `isPublished`;