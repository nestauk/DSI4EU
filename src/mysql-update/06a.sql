ALTER TABLE `translate`
  ADD `it` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `fr`,
  ADD `es` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `it`,
  ADD `ca` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `es`;