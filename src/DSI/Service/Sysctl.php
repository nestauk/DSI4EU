<?php

namespace DSI\Service;

class Sysctl
{
    public static $version = '1.3u';

    public static function echoVersion()
    {
        echo 'v=' . self::$version;
    }
}