<?php

namespace DSI\Service;

class App
{
    const DEV = 'dev';
    const TEST = 'test';
    const LIVE = 'live';

    private static $env;

    public static function setEnv($env)
    {
        self::$env = $env;
    }

    public static function getEnv()
    {
        return self::$env;
    }
}