ALTER TABLE `case-studies` ADD `isFeaturedOnSlider` TINYINT(1) NOT NULL AFTER `isPublished`, ADD `isFeaturedOnHomePage` TINYINT(1) NOT NULL AFTER `isFeaturedOnSlider`;