<?php

namespace DSI\Service;

use Services\App;

class Sysctl
{
    public static $version = '1.4c.18';

    public static function echoVersion()
    {
        if (in_array(App::getEnv(), [App::DEV, App::TEST]))
            echo 'v=' . rand(1, 99999);
        else
            echo 'v=' . self::$version;
    }
}