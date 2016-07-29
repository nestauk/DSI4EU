<?php

namespace DSI\Controller;

use DSI\Repository\CaseStudyRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;

class CaseStudyController
{
    /** @var int */
    public $caseStudyID;

    public function exec()
    {
        $loggedInUser = null;

        $authUser = new Auth();
        if ($authUser->isLoggedIn()) {
            $userRepo = new UserRepository();
            $loggedInUser = $userRepo->getById($authUser->getUserId());
        }

        $userCanAddCaseStudy = (bool)($loggedInUser AND ($loggedInUser->isCommunityAdmin() OR $loggedInUser->isEditorialAdmin()));

        $caseStudyRepo = new CaseStudyRepository();
        $caseStudy = $caseStudyRepo->getById($this->caseStudyID);

        $caseStudies = $caseStudyRepo->getAll();
        $caseStudies = array_splice($caseStudies, 0, 3);

        $pageTitle = $caseStudy->getTitle();
        require __DIR__ . '/../../../www/case-study.php';
    }
}