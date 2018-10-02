<?php

namespace Controllers\CaseStudies;

use Models\CaseStudy;
use DSI\Entity\User;
use DSI\Service\Auth;
use DSI\Service\Translate;
use Models\Cluster;
use Models\Relationship\ClusterLang;
use Models\Tag;
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

        // $caseStudyRepository = new CaseStudyRepo();
        if ($userCanManageCaseStudies)
            // $caseStudies = $caseStudyRepository->getAll();
            $caseStudies = CaseStudy
                ::with('caseStudyTags')
                ->orderBy(CaseStudy::Id, 'desc')
                ->get();
        else
            // $caseStudies = $caseStudyRepository->getAllPublished();
            $caseStudies = CaseStudy
                ::where(CaseStudy::IsPublished, true)
                ->with('caseStudyTags')
                ->orderBy(CaseStudy::Id, 'desc')
                ->get();

        if ($this->format == 'json') {
            echo json_encode(array_map(function (CaseStudy $caseStudy) use ($urlHandler) {
                return [
                    'id' => $caseStudy->getId(),
                    'cardImage' => $caseStudy->{CaseStudy::CardImage},
                    'cardColour' => $caseStudy->{CaseStudy::CardColour},
                    'logo' => $caseStudy->{CaseStudy::Logo},
                    'introCardText' => $caseStudy->{CaseStudy::IntroCardText},
                    'title' => $caseStudy->{CaseStudy::Title},
                    'isPublished' => $caseStudy->{CaseStudy::IsPublished},
                    'url' => $urlHandler->caseStudyModel($caseStudy),
                ];
            }, $caseStudies));
        } else {
            View::setPageTitle('Case studies - DSI4EU');
            return View::render(__DIR__ . '/../../Views/case-studies/case-studies.php', [
                'loggedInUser' => $loggedInUser,
                'userCanManageCaseStudies' => $userCanManageCaseStudies,
                'caseStudies' => $caseStudies,
                'tags' => Tag
                    ::where(Tag::IsMain, true)
                    ->orderBy(Tag::Order, 'desc')
                    ->get(),
            ]);
        }
    }

    /**
     * @param User|null $loggedInUser
     * @return bool
     */
    private function userCanManageCaseStudies($loggedInUser): bool
    {
        $userCanAddCaseStudy = (bool)($loggedInUser AND ($loggedInUser->isCommunityAdmin() OR $loggedInUser->isEditorialAdmin()));
        return $userCanAddCaseStudy;
    }
}