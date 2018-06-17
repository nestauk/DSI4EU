<?php

namespace Controllers\CaseStudies;

use DSI\Repository\CaseStudyRepo;
use DSI\Service\Auth;
use Services\URL;
use Services\View;

class CaseStudyController
{
    /** @var int */
    public $caseStudyID;

    public function exec()
    {
        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();

        $userCanAddCaseStudy = (bool)($loggedInUser AND ($loggedInUser->isCommunityAdmin() OR $loggedInUser->isEditorialAdmin()));

        $caseStudyRepo = new CaseStudyRepo();
        $caseStudy = $caseStudyRepo->getById($this->caseStudyID);

        $caseStudies = $caseStudyRepo->getAll();
        $caseStudies = array_splice($caseStudies, 0, 3);

        View::render(__DIR__ . '/../../Views/case-studies/case-study.php', [
            'loggedInUser' => $loggedInUser,
            'userCanAddCaseStudy' => $userCanAddCaseStudy,
            'caseStudy' => $caseStudy,
            'caseStudies' => $caseStudies,
        ]);
    }
}