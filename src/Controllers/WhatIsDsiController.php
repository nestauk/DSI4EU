<?php

namespace Controllers;

use DSI\Entity\User;
use DSI\Repository\CaseStudyRepo;
use DSI\Service\Auth;
use Services\URL;
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
        View::setPageTitle('What is DSI? - DSI4EU');
        View::setPageDescription(__('Find out more about how digital social innovation (DSI) brings together people and technology to tackle social and environmental challenges.'));
        return View::render(__DIR__ . '/../Views/what-is-dsi/en.php', [
            'authUser' => $this->authUser,
            'loggedInUser' => $this->loggedInUser,
            'homePageCaseStudies' => (new CaseStudyRepo())->getHomePageStudiesLast(3),
        ]);
    }
}