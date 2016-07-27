<?php

namespace DSI\Service;

class Sysctl
{
    public static $version = '1.1m';

    public static function echoVersion()
    {
        echo 'v=' . self::$version;
    }
}