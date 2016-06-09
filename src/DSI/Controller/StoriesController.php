<?php

namespace DSI\Controller;

use DSI\Repository\StoryRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;

class StoriesController
{
    public function exec()
    {
        $authUser = new Auth();
        if ($authUser->isLoggedIn())
            $loggedInUser = (new UserRepository())->getById($authUser->getUserId());

        $stories = (new StoryRepository())->getAll();

        require __DIR__ . '/../../../www/stories.php';
    }
}