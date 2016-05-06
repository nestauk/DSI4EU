<?php
/**
 * Created by PhpStorm.
 * User: apandele
 * Date: 25/04/2016
 * Time: 15:45
 */

namespace DSI\Controller;

use DSI\Service\Auth;
use DSI\Service\URL;

class StoriesController
{
    public function exec()
    {
        //$authUser = new Auth();
        //$authUser->ifLoggedInRedirectTo(URL::myProfile());

        require __DIR__ . '/../../../www/stories.php';
    }
}