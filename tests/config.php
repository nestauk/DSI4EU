<?php

require __DIR__ . '/../src/kernel.php';
$config = Kernel::loadConfigFile(__DIR__ . '/../config/app.php');
$config['env'] = 'test';
Kernel::setConfig($config);

\DSI\Repository\OrganisationRepoInAPC::setApcKey(
    'digitalSocialTest:organisations'
);
\DSI\Repository\ProjectRepoInAPC::setApcKey(
    'digitalSocialTest:projects'
);