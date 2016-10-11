<?php

namespace DSI\Controller;

use DSI\Repository\CaseStudyRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\ProjectRepository;
use DSI\Service\Auth;

class HomeController
{
    public $format = 'html';

    public function exec()
    {
        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();
        
        $hideSearch = true;
        $sliderCaseStudies = (new CaseStudyRepository())->getAllPublishedForSlider();
        $homePageCaseStudies = (new CaseStudyRepository())->getHomePageStudiesLast(3);

        $organisationsCount = (new \DSI\Repository\OrganisationRepositoryInAPC())->countAll();
        $projectsCount = (new \DSI\Repository\ProjectRepositoryInAPC())->countAll();

        $isIndexPage = true;

        require __DIR__ . '/../../../www/views/home.php';
    }
}