<?php

namespace DSI\Controller;

use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class PersonalDetailsController
{
    public function __construct()
    {
    }

    public function exec()
    {
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo(URL::login());

        $user = (new UserRepository())->getById($authUser->getUserId());
        $userID = $user->getId();

        $loggedInUser = (new UserRepository())->getById($authUser->getUserId());
        $angularModules['fileUpload'] = true;
        require __DIR__ . '/../../../www/personal-details.php';
    }
}