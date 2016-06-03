ALTER TABLE `organisations` ADD `importID` CHAR(255) NOT NULL AFTER `partnersCount`;
ALTER TABLE `projects` ADD `importID` CHAR(255) NOT NULL AFTER `organisationsCount`;

TRUNCATE `countries`;
TRUNCATE `country-regions`;
TRUNCATE `impact-tags`;
TRUNCATE `organisation-member-requests`;
TRUNCATE `organisation-members`;
TRUNCATE `organisation-projects`;
TRUNCATE `organisation-sizes`;
TRUNCATE `organisation-tags`;
TRUNCATE `organisation-types`;
TRUNCATE `organisations`;
TRUNCATE `password-recovery`;
TRUNCATE `project-email-invitations`;
TRUNCATE `project-impact-tags-a`;
TRUNCATE `project-impact-tags-b`;
TRUNCATE `project-impact-tags-c`;
TRUNCATE `project-member-requests`;
TRUNCATE `project-members`;
TRUNCATE `project-post-comment-replies`;
TRUNCATE `project-post-comments`;
TRUNCATE `project-posts`;
TRUNCATE `project-tags`;
TRUNCATE `projects`;
TRUNCATE `tags-for-organisations`;
TRUNCATE `tags-for-projects`;

