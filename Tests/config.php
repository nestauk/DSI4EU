<?php

define('NO_SESSION', true);
require_once(__DIR__ . "/../src/config.php");

\DSI\Repository\OrganisationRepositoryInAPC::setApcKey(
    'digitalSocialTest:organisations'
);
\DSI\Repository\ProjectRepositoryInAPC::setApcKey(
    'digitalSocialTest:projects'
);

\DSI\Service\SQL::credentials(array(
    'username' => 'root',
    'password' => '',
    'db' => 'dsi-test',
));