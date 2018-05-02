<?php

require __DIR__ . '/src/config.php';
require __DIR__ . '/routes/console.php';

$router = new CliRouter();
$router->exec($argv);