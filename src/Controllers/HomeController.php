<?php

namespace Controllers;

use DSI\Repository\CaseStudyRepo;
use DSI\Service\Auth;
use Services\View;

class HomeController
{
    public function exec()
    {
        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();

        View::setPageDescription(__('Supporting Digital Social Innovation (DSI), tech for good and civic tech through research, policy and practical support.'));
        return View::render(__DIR__ . '/../Views/home.php', [
            'loggedInUser' => $loggedInUser,
            'hideSearch' => true,
            'sliderCaseStudies' => (new CaseStudyRepo())->getAllPublishedForSlider(),
            'homePageCaseStudies' => (new CaseStudyRepo())->getHomePageStudiesLast(3),
            'organisationsCount' => (new \DSI\Repository\OrganisationRepoInAPC())->countAll(),
            'projectsCount' => (new \DSI\Repository\ProjectRepoInAPC())->countAll(),
            'isIndexPage' => true,
        ]);
    }
}