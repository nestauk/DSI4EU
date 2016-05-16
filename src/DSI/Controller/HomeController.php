<?php

namespace DSI\Controller;

use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class HomeController
{
    public function exec()
    {
        $authUser = new Auth();

        if ($authUser->getUserId())
            $loggedInUser = (new UserRepository())->getById($authUser->getUserId());
        else
            $loggedInUser = null;

        $hideSearch = true;

        $data = [
            'loggedInUser' => $loggedInUser,
            'hideSearch' => $hideSearch,
        ];
        require __DIR__ . '/../../../www/home.php';
    }
}