<?php

define('NO_SESSION', true);
require_once(__DIR__ . "/../src/config.php");

\DSI\Service\SQL::credentials(array(
    'username' => 'root',
    'password' => '',
    'db' => 'dsi4eu-test',
));