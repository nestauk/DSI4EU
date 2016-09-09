<?php

namespace DSI\Controller;

use DSI\Repository\CaseStudyRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

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

        $caseStudyRepo = new CaseStudyRepository();
        $caseStudy = $caseStudyRepo->getById($this->caseStudyID);

        $caseStudies = $caseStudyRepo->getAll();
        $caseStudies = array_splice($caseStudies, 0, 3);

        $pageTitle = $caseStudy->getTitle();
        require __DIR__ . '/../../../www/views/case-study.php';
    }
}