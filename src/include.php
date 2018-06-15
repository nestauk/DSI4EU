<?php

require __DIR__ . '/kernel.php';
$config = Kernel::loadConfigFile(__DIR__ . '/../config/app.php');
Kernel::setConfig($config);