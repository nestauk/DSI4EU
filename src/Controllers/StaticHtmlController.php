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
        View::setPageTitle('Partners - DSI4EU');
        View::setPageDescription(__('Find out more about the DSI4EU partners: Nesta, betterplace lab, Fab Lab Barcelona, WeMake, Barcelona Activa and ePaństwo Foundation.'));
        if (Translate::getCurrentLang() === 'en')
            return View::render(__DIR__ . '/../Views/partners/partners_en.php', [
                'loggedInUser' => $this->loggedInUser,
            ]);
        else
            return View::render(__DIR__ . '/../Views/partners/partners.php', [
                'loggedInUser' => $this->loggedInUser,
            ]);
    }

    public function cookiesPolicy()
    {
        View::setPageTitle('Cookies Policy - DSI4EU');
        return View::render(__DIR__ . '/../Views/cookies-policy.php', [
            'loggedInUser' => $this->loggedInUser,
        ]);
    }
}