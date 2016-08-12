CREATE TABLE `translate` (
  `index` char(255) NOT NULL,
  `details` text NOT NULL,
  `en` text NOT NULL,
  `de` text NOT NULL,
  `fr` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `translate`
  ADD UNIQUE KEY `index` (`index`);
