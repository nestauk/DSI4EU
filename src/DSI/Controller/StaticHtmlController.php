<?php

namespace DSI\Controller;

use DSI\Service\Auth;

class StaticHtmlController
{
    public $format = 'html';
    public $view;

    public function exec()
    {
        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();

        if ($this->format == 'txt')
            header("Content-Type: text/plain");

        require __DIR__ . '/../../../www/views/' . $this->view;
    }
}