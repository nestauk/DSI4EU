<?php

namespace DSI\Controller;

use DSI\Entity\User;
use DSI\Repository\CaseStudyRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class CaseStudiesController
{
    /** @var string */
    public $format = 'html';

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();
        $userCanManageCaseStudies = $this->userCanManageCaseStudies($loggedInUser);

        if ($this->format == 'json') {

        } else {
            $caseStudyRepository = new CaseStudyRepository();
            if ($userCanManageCaseStudies)
                $caseStudies = $caseStudyRepository->getAll();
            else
                $caseStudies = $caseStudyRepository->getAllPublished();

            require __DIR__ . '/../../../www/views/case-studies.php';
        }
    }

    /**
     * @param User|null $loggedInUser
     * @return bool
     */
    private function userCanManageCaseStudies($loggedInUser):bool
    {
        $userCanAddCaseStudy = (bool)($loggedInUser AND ($loggedInUser->isCommunityAdmin() OR $loggedInUser->isEditorialAdmin()));
        return $userCanAddCaseStudy;
    }
}