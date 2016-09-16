CREATE TABLE `app-registrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `loggedInUserID` int(10) UNSIGNED NOT NULL,
  `registeredUserID` int(10) UNSIGNED NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `app-registrations`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `app-registrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;