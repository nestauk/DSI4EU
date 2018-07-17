<?php

namespace Services;

class Assets
{
    public static $version = '1.4c.21';

    public static function version()
    {
        if (in_array(App::getEnv(), [App::DEV, App::TEST]))
            return rand(1, 99999);
        else
            return self::$version;
    }
}
