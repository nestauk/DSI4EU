<?php

namespace Controllers\CaseStudies;

use DSI\Entity\User;
use DSI\Repository\OrganisationRepoInAPC;
use DSI\Repository\ProjectRepoInAPC;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\JsModules;
use Models\Tag;
use Services\URL;
use DSI\UseCase\CaseStudy\CaseStudyCreate;
use Services\View;

class CaseStudyAddController
{
    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());
        $loggedInUser = $authUser->getUser();

        if (!$this->userCanAddCaseStudy($loggedInUser))
            go_to($urlHandler->home());

        $tags = Tag::where(Tag::IsMain, 1)
            ->orderBy(Tag::Order, 'desc')
            ->get();

        if (isset($_POST['add'])) {
            try {
                $createCaseStudy = new CaseStudyCreate();
                $createCaseStudy->data()->title = $_POST['title'] ?? '';
                $createCaseStudy->data()->introCardText = $_POST['introCardText'] ?? '';
                $createCaseStudy->data()->introPageText = $_POST['introPageText'] ?? '';
                $createCaseStudy->data()->infoText = $_POST['infoText'] ?? '';
                $createCaseStudy->data()->mainText = $_POST['mainText'] ?? '';
                $createCaseStudy->data()->projectStartDate = $_POST['projectStartDate'] ?? '';
                $createCaseStudy->data()->projectEndDate = $_POST['projectEndDate'] ?? '';
                $createCaseStudy->data()->url = $_POST['url'] ?? '';
                $createCaseStudy->data()->buttonLabel = $_POST['buttonLabel'] ?? '';
                $createCaseStudy->data()->cardColour = $_POST['cardColour'] ?? '';
                $createCaseStudy->data()->isPublished = $_POST['isPublished'] ?? '';
                $createCaseStudy->data()->positionOnHomePage = $_POST['positionOnHomePage'] ?? false;
                $createCaseStudy->data()->cardBgImage = $_POST['cardImage'] ?? '';
                $createCaseStudy->data()->projectID = (int)($_POST['projectID'] ?? 0);
                $createCaseStudy->data()->organisationID = (int)($_POST['organisationID'] ?? 0);
                $createCaseStudy->tagIDs = (array)$_POST['tags'];
                $createCaseStudy->exec();
                $caseStudy = $createCaseStudy->getCaseStudy();

                echo json_encode([
                    'code' => 'ok',
                    'url' => $urlHandler->caseStudy($caseStudy),
                ]);
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors(),
                ]);
            }
            return;
        }

        return View::render(__DIR__ . '/../../Views/case-studies/case-study-add.php', [
            'projects' => (new ProjectRepoInAPC())->getAll(),
            'organisations' => (new OrganisationRepoInAPC())->getAll(),
            'tags' => $tags,
        ]);
    }

    /**
     * @param User $loggedInUser
     * @return bool
     */
    private function userCanAddCaseStudy(User $loggedInUser): bool
    {
        $userCanAddCaseStudy = (bool)($loggedInUser AND ($loggedInUser->isCommunityAdmin() OR $loggedInUser->isEditorialAdmin()));
        return $userCanAddCaseStudy;
    }
}