<?php

namespace DSI\Controller;

use DSI\Repository\CaseStudyRepo;
use DSI\Repository\OrganisationRepo;
use DSI\Repository\ProjectRepo;
use DSI\Service\Auth;

class HomeController
{
    public $format = 'html';

    public function exec()
    {
        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();
        
        $hideSearch = true;
        $sliderCaseStudies = (new CaseStudyRepo())->getAllPublishedForSlider();
        $homePageCaseStudies = (new CaseStudyRepo())->getHomePageStudiesLast(3);

        $organisationsCount = (new \DSI\Repository\OrganisationRepoInAPC())->countAll();
        $projectsCount = (new \DSI\Repository\ProjectRepoInAPC())->countAll();

        $isIndexPage = true;

        require __DIR__ . '/../../Views/home.php';
    }
}