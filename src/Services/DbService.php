<?php
namespace Services;

use Illuminate\Database\Capsule\Manager as Capsule;

class DbService{
    static function init(){
        $capsule = new Capsule;

        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => \DSI\Service\SQL::getCredentials()['db'],
            'username'  => \DSI\Service\SQL::getCredentials()['username'],
            'password'  => \DSI\Service\SQL::getCredentials()['password'],
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}