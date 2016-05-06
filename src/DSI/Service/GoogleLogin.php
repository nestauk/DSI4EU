<?php

namespace DSI\Service;

use League\OAuth2\Client\Provider\Google;

class GoogleLogin
{
    private static $credentials;

    /** @var Google */
    private $provider;

    public static function setCredentials($credentials)
    {
        self::$credentials = $credentials;
    }

    public function __construct()
    {
        $this->provider = new Google(self::$credentials);
    }

    public function getUrl()
    {
        $authUrl = $this->provider->getAuthorizationUrl();
        $_SESSION['oauth2state'] = $this->provider->getState();

        return $authUrl;
    }

    /**
     * @return Google
     */
    public function getProvider()
    {
        return $this->provider;
    }
}