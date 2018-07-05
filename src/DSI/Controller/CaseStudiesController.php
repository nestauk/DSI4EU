<?php

namespace DSI\Controller;

use DSI\Entity\CaseStudy;
use DSI\Entity\User;
use DSI\Repository\CaseStudyRepo;
use DSI\Service\Auth;
use Services\URL;
use Services\View;

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

        $caseStudyRepository = new CaseStudyRepo();
        if ($userCanManageCaseStudies)
            $caseStudies = $caseStudyRepository->getAll();
        else
            $caseStudies = $caseStudyRepository->getAllPublished();

        if ($this->format == 'json') {
            echo json_encode(array_map(function(CaseStudy $caseStudy) use ($urlHandler){
                return [
                    'id' => $caseStudy->getId(),
                    'cardImage' => $caseStudy->getCardImage(),
                    'cardColour' => $caseStudy->getCardColour(),
                    'logo' => $caseStudy->getLogo(),
                    'introCardText' => $caseStudy->getIntroCardText(),
                    'title' => $caseStudy->getTitle(),
                    'isPublished' => $caseStudy->isPublished(),
                    'url' => $urlHandler->caseStudy($caseStudy),
                ];
            }, $caseStudies));
        } else {
            View::setPageTitle('Case studies - DSI4EU');
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