<?php

define('NO_SESSION', true);
require_once(__DIR__ . "/../src/config.php");

\DSI\Repository\OrganisationRepoInAPC::setApcKey(
    'digitalSocialTest:organisations'
);
\DSI\Repository\ProjectRepoInAPC::setApcKey(
    'digitalSocialTest:projects'
);

\DSI\Service\SQL::setCredentials(array(
    'username' => 'root',
    'password' => '',
    'db' => 'dsi-test',
));

\DSI\Service\App::setEnv(\DSI\Service\App::TEST);