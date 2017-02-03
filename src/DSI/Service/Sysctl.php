<?php

namespace DSI\Service;

class Sysctl
{
    public static $version = '1.4b';

    public static function echoVersion()
    {
        echo 'v=' . self::$version;
    }
}