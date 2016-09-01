<?php

namespace DSI\Controller;

use DSI\Repository\CaseStudyRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\StoryRepository;
use DSI\Repository\UserRepository;
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
        $organisationCount = (new OrganisationRepository())->countAll();
        $projectCount = (new ProjectRepository())->countAll();

        require __DIR__ . '/../../../www/home.php';
    }
}