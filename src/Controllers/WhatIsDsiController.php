<?php

namespace Controllers;

use DSI\Entity\User;
use DSI\NotFound;
use DSI\Service\Auth;
use DSI\Service\JsModules;
use DSI\Service\Translate;
use Services\URL;
use Models\ClusterLang;
use Services\Request;
use Services\Response;
use Services\View;

class WhatIsDsiController
{
    /** @var URL */
    private $urlHandler;

    /** @var User */
    private $loggedInUser;

    /** @var Auth */
    private $authUser;

    public function __construct()
    {
        $this->urlHandler = new URL();
        $this->authUser = new Auth();
        $this->loggedInUser = $this->authUser->getUserIfLoggedIn();
    }

    public function get()
    {
        return View::render(__DIR__ . '/../Views/what-is-dsi/en.php', [
            'authUser' => $this->authUser,
            'loggedInUser' => $this->loggedInUser,
        ]);
    }
}