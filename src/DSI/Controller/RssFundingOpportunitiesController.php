<?php

namespace DSI\Controller;

use DSI\Repository\FundingRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class RssFundingOpportunitiesController
{
    public function exec()
    {
        $urlHandler = new URL();

        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();

        $fundings = (new FundingRepository())->getAll();

        header("Content-Type: application/rss+xml");
        require __DIR__ . '/../../../www/views/rss-funding-opportunities.php';
    }
}