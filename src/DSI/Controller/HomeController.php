<?php

namespace DSI\Controller;

use DSI\Repository\ProjectRepository;
use DSI\Repository\StoryRepository;
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

        if ($authUser->getUserId()) {
            $totalProjects = (new ProjectRepository())->countProjects();
            $latestStories = (new StoryRepository())->getLast(3);

            require __DIR__ . '/../../../www/home-dashboard.php';
        } else {
            $hideSearch = true;
            require __DIR__ . '/../../../www/home.php';
        }
    }
}