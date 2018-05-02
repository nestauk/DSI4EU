<?php

namespace DSI\Controller;

use DSI\Repository\FundingRepo;
use DSI\Service\Auth;
use Services\URL;

class RssFundingOpportunitiesController
{
    public function exec()
    {
        $urlHandler = new URL();

        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();

        $fundings = (new FundingRepo())->getAll();

        header("Content-Type: application/rss+xml");
        require __DIR__ . '/../../../www/views/rss-funding-opportunities.php';
    }
}