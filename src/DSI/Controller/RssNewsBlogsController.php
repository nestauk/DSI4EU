<?php

namespace DSI\Controller;

use DSI\Repository\StoryRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class RssNewsBlogsController
{
    public function exec()
    {
        $urlHandler = new URL();

        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();

        $stories = (new StoryRepository())->getAllPublished();

        header("Content-Type: application/rss+xml");
        require __DIR__ . '/../../../www/views/rss-news-and-blogs.php';
    }
}