<?php

namespace Services;

class App
{
    const DEV = 'dev';
    const TEST = 'test';
    const LIVE = 'live';

    private static $env;
    private static $canCreateProjects = false;
    private static $waitingApprovalEmailAddress = '';

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

    /**
     * @return string
     */
    public static function getWaitingApprovalEmailAddress(): string
    {
        return self::$waitingApprovalEmailAddress;
    }

    /**
     * @param string $emailAddress
     */
    public static function setWaitingApprovalEmailAddress(string $emailAddress)
    {
        self::$waitingApprovalEmailAddress = $emailAddress;
    }
}