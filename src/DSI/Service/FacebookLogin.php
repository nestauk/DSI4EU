<?php

namespace DSI\Service;

use League\OAuth2\Client\Provider\Facebook;

class FacebookLogin
{
    private static $credentials;

    private $provider;

    public static function setCredentials($credentials)
    {
        self::$credentials = $credentials;
    }

    public function __construct()
    {
        $this->provider = new Facebook(self::$credentials);
    }

    public function getUrl()
    {
        $authUrl = $this->provider->getAuthorizationUrl([
            'scope' => ['email'],
        ]);
        $_SESSION['oauth2state'] = $this->provider->getState();

        return $authUrl;
    }

    /**
     * @return Facebook
     */
    public function getProvider()
    {
        return $this->provider;
    }
}