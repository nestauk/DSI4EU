<?php

require __DIR__ . '/../src/kernel.php';

$config = Kernel::loadConfigFile(__DIR__ . '/app.php');
$command = implode(' ', $_SERVER['argv']);
if (preg_match('/-e test/', $command) OR preg_match('/-environment test/', $command)) {
    $config['env'] = \Services\App::TEST;
}
Kernel::setConfig($config);

return [
    'paths' => [
        'migrations' => __DIR__ . '/../db/migrations',
        'seeds' => __DIR__ . '/../db/seeds',
    ],
    'environments' => [
        "default_migration_table" => "phinxlog",
        "default_database" => "production",
        "production" => [
            'adapter' => 'mysql',
            'host' => $config['mysql']['host'],
            'name' => $config['mysql']['db'],
            'user' => $config['mysql']['username'],
            'pass' => $config['mysql']['password'],
            'port' => '3306',
            'charset' => 'utf8',
        ],
        "test" => [
            'adapter' => 'mysql',
            'host' => $config['mysql-test']['host'],
            'name' => $config['mysql-test']['db'],
            'user' => $config['mysql-test']['username'],
            'pass' => $config['mysql-test']['password'],
            'port' => '3306',
            'charset' => 'utf8',
        ],
    ],
    'version_order' => 'creation'
];