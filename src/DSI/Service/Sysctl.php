<?php

namespace DSI\Service;

class Sysctl
{
    public static $version = '1.2v';

    public static function echoVersion()
    {
        echo 'v=' . self::$version;
    }
}