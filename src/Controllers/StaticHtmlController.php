<?php

namespace Controllers;

use DSI\Service\Auth;
use Services\View;

class StaticHtmlController
{
    /** @var Auth */
    private $authUser;

    /** @var \DSI\Entity\User|null */
    private $loggedInUser;

    public $format = 'html';
    public $view;

    public function __construct()
    {
        $this->authUser = new Auth();
        $this->loggedInUser = $this->authUser->getUserIfLoggedIn();
    }

    public function privacyPolicy()
    {
        return View::render(__DIR__ . '/../Views/privacy-policy.php', [
            'loggedInUser' => $this->loggedInUser,
        ]);
    }

    public function cookiesPolicy()
    {
        return View::render(__DIR__ . '/../Views/cookies-policy.php', [
            'loggedInUser' => $this->loggedInUser,
        ]);
    }
}