<?php

namespace Controllers;

use DSI\Service\Auth;
use DSI\Service\Translate;
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

    public function partners()
    {
        $data = [
            'loggedInUser' => $this->loggedInUser,
        ];

        if (Translate::getCurrentLang() === 'en')
            return View::render(__DIR__ . '/../Views/partners/partners_en.php', $data);
        else
            return View::render(__DIR__ . '/../Views/partners/partners.php', $data);
    }

    public function cookiesPolicy()
    {
        View::setPageTitle('Cookies Policy - DSI4EU');
        return View::render(__DIR__ . '/../Views/cookies-policy.php', [
            'loggedInUser' => $this->loggedInUser,
        ]);
    }
}