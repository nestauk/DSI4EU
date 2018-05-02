<?php

define('NO_SESSION', true);
require_once __DIR__ . '/../src/include.php';

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

\Services\App::setEnv(\Services\App::TEST);