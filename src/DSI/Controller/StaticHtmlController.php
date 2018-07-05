<?php

namespace DSI\Controller;

use DSI\Entity\User;
use DSI\Service\Auth;
use Services\View;

class StaticHtmlController
{
    public $format = 'html';
    public $view;

    /** @var User */
    private $loggedInUser;

    public function __construct()
    {
        $authUser = new Auth();
        $this->loggedInUser = $authUser->getUserIfLoggedIn();
    }

    public function exec()
    {
        if ($this->format == 'txt')
            header("Content-Type: text/plain");


        $loggedInUser = $this->loggedInUser;
        require __DIR__ . '/../../../www/views/' . $this->view;
    }

    public function contact()
    {
        View::setPageTitle('Contact - DSI4EU');
        return View::render(__DIR__ . '/../../../www/views/contact-dsi.php', [
            'loggedInUser' => $this->loggedInUser
        ]);
    }
}