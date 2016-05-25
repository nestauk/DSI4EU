SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `project-email-invitations` (
  `projectID` int(10) UNSIGNED NOT NULL,
  `byUserID` int(10) UNSIGNED NOT NULL,
  `email` char(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `project-email-invitations`
  ADD UNIQUE KEY `projectID` (`projectID`,`email`);

