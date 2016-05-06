<?php

namespace DSI\Controller;

use DSI\Service\Auth;
use DSI\Service\URL;

class HomeController
{
    public function exec()
    {
        // $authUser = new Auth();

        require __DIR__ . '/../../../www/home.php';
    }
}