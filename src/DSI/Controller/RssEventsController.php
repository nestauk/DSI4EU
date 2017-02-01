<?php

namespace DSI\Controller;

use DSI\Repository\EventRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class RssEventsController
{
    public function exec()
    {
        $urlHandler = new URL();

        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();

        $events = (new EventRepository())->getAll();

        header("Content-Type: application/rss+xml");
        require __DIR__ . '/../../../www/views/rss-events.php';
    }
}