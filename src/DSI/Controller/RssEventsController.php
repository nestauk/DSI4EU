<?php

namespace DSI\Controller;

use DSI\Repository\EventRepo;
use DSI\Service\Auth;
use DSI\Service\URL;

class RssEventsController
{
    public function exec()
    {
        $urlHandler = new URL();

        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();

        $events = (new EventRepo())->getAll();

        header("Content-Type: application/rss+xml");
        require __DIR__ . '/../../../www/views/rss-events.php';
    }
}