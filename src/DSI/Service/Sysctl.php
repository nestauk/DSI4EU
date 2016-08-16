<?php

namespace DSI\Service;

class Sysctl
{
    public static $version = '1.2j';

    public static function echoVersion()
    {
        echo 'v=' . self::$version;
    }
}