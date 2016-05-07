<?php

namespace DSI\Controller;

use DSI\Service\Auth;
use DSI\Service\URL;

class ProjectsController
{
    public function exec()
    {
        //$authUser = new Auth();
        //$authUser->ifLoggedInRedirectTo(URL::myProfile());

        require __DIR__ . '/../../../www/projects.php';
    }
}