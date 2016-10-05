<?php

namespace DSI\Controller;

use DSI\Entity\User;
use DSI\Repository\OrganisationRepositoryInAPC;
use DSI\Repository\ProjectRepositoryInAPC;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\CaseStudy\CaseStudyCreate;

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

        $angularModules['fileUpload'] = true;
        $projects = (new ProjectRepositoryInAPC())->getAll();
        $organisations = (new OrganisationRepositoryInAPC())->getAll();
        require(__DIR__ . '/../../../www/views/case-study-add.php');
    }

    /**
     * @param User $loggedInUser
     * @return bool
     */
    private function userCanAddCaseStudy(User $loggedInUser):bool
    {
        $userCanAddCaseStudy = (bool)($loggedInUser AND ($loggedInUser->isCommunityAdmin() OR $loggedInUser->isEditorialAdmin()));
        return $userCanAddCaseStudy;
    }
}