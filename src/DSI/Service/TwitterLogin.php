<?php

namespace DSI\Service;

use League\OAuth1\Client\Server\Twitter;

class TwitterLogin
{
    private static $credentials;

    private $provider;

    public static function setCredentials($credentials)
    {
        self::$credentials = $credentials;
    }

    public function __construct()
    {
        $this->provider = new Twitter(self::$credentials);
    }

    /**
     * @return Twitter
     */
    public function getProvider()
    {
        return $this->provider;
    }
}