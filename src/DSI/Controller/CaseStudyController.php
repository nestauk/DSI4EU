<?php

namespace DSI\Controller;

use DSI\Repository\CaseStudyRepo;
use DSI\Service\Auth;
use Services\URL;

class CaseStudyController
{
    /** @var int */
    public $caseStudyID;

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();

        $userCanAddCaseStudy = (bool)($loggedInUser AND ($loggedInUser->isCommunityAdmin() OR $loggedInUser->isEditorialAdmin()));

        $caseStudyRepo = new CaseStudyRepo();
        $caseStudy = $caseStudyRepo->getById($this->caseStudyID);

        $caseStudies = $caseStudyRepo->getAll();
        $caseStudies = array_splice($caseStudies, 0, 3);

        $pageTitle = $caseStudy->getTitle();
        require __DIR__ . '/../../../www/views/case-study.php';
    }
}