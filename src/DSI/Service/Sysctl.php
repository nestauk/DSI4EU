<?php

namespace DSI\Service;

class Sysctl
{
    public static $version = '1.4c.13';

    public static function echoVersion()
    {
        if (App::getEnv() == App::DEV)
            echo 'v=' . rand(1, 99999);
        else
            echo 'v=' . self::$version;
    }
}