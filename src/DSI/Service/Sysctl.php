<?php

namespace DSI\Service;

class Sysctl
{
    public static $version = '1.1t';

    public static function echoVersion()
    {
        echo 'v=' . self::$version;
    }
}