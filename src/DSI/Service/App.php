<?php

namespace DSI\Service;

class App
{
    const DEV = 'dev';
    const TEST = 'test';
    const LIVE = 'live';

    private static $env;
    private static $canCreateProjects = false;

    public static function setEnv($env)
    {
        self::$env = $env;
    }

    public static function getEnv()
    {
        return self::$env;
    }

    public static function canCreateProjects(): bool
    {
        return self::$canCreateProjects;
    }

    public static function setCanCreateProjects(bool $canCreateProjects)
    {
        self::$canCreateProjects = $canCreateProjects;
    }
}