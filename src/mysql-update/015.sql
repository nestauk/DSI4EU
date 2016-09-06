CREATE TABLE `mails` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `mails`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `mails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
  
