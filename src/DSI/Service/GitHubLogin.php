<?php

namespace DSI\Service;

use League\OAuth2\Client\Provider\Github;

class GitHubLogin
{
    private static $credentials;

    private $provider;

    public static function setCredentials($credentials)
    {
        self::$credentials = $credentials;
    }

    public function __construct()
    {
        $this->provider = new Github(self::$credentials);
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
     * @return Github
     */
    public function getProvider()
    {
        return $this->provider;
    }
}