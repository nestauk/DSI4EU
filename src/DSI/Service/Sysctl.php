<?php

namespace DSI\Service;

class Sysctl
{
    public static $version = '1.4c.17';

    public static function echoVersion()
    {
        if (in_array(App::getEnv(), [App::DEV, App::TEST]))
            echo 'v=' . rand(1, 99999);
        else
            echo 'v=' . self::$version;
    }
}