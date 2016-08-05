<?php

namespace DSI\Service;

class Sysctl
{
    public static $version = '1.2b';

    public static function echoVersion()
    {
        echo 'v=' . self::$version;
    }
}