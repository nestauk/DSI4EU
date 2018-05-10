<?php
$config = require __DIR__ . '/app.php';

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
        ]
    ],
    'version_order' => 'creation'
];