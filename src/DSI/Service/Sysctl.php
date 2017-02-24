<?php

namespace DSI\Service;

class Sysctl
{
    public static $version = '1.4c.9';

    public static function echoVersion()
    {
        echo 'v=' . self::$version;
        // echo 'v=' . rand(1, 99999);
    }
}