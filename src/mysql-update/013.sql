CREATE TABLE `funding-sources` (
  `id` int(11) NOT NULL,
  `title` char(255) NOT NULL,
  `url` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `funding-sources`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `funding-sources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
  
CREATE TABLE `fundings` (
 `id` INT NOT NULL AUTO_INCREMENT ,
 `title` CHAR(255) NOT NULL ,
 `url` VARCHAR(1024) NOT NULL ,
 `description` VARCHAR(10000) NOT NULL ,
 `closingDate` DATE NOT NULL ,
 `fundingSourceID` INT NOT NULL ,
 `countryID` INT NOT NULL ,
 PRIMARY KEY (`id`)
) ENGINE = InnoDB;