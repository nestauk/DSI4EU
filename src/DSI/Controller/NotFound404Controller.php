<?php

namespace DSI\Controller;

use DSI\Service\Auth;

class NotFound404Controller
{
    public function exec()
    {
        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();

        require __DIR__ . '/../../../www/404-not-found.php';
    }
}