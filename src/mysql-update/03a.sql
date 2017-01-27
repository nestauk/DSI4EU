CREATE TABLE `auth-tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `selector` char(12) NOT NULL,
  `token` char(64) NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastUse` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `auth-tokens`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `auth-tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;