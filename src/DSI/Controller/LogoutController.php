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

class LogoutController
{
    public function exec()
    {
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo(URL::login());

        $authUser->removeUserFromSession($authUser->getUserId());
        go_to(URL::home());
    }
}