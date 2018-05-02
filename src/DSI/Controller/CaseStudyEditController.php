<?php

namespace DSI\Controller;

use DSI\AccessDenied;
use DSI\Entity\Image;
use DSI\Entity\CaseStudy;
use DSI\Entity\Organisation;
use DSI\Entity\Project;
use DSI\Entity\User;
use DSI\Repository\CaseStudyRepo;
use DSI\Repository\OrganisationRepoInAPC;
use DSI\Repository\ProjectRepoInAPC;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\JsModules;
use Services\URL;
use DSI\UseCase\CaseStudy\CaseStudyEdit;

class CaseStudyEditController
{
    public $caseStudyID;
    public $format = 'html';

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());
        $loggedInUser = $authUser->getUser();

        $userCanAddCaseStudy = (bool)($loggedInUser AND ($loggedInUser->isCommunityAdmin() OR $loggedInUser->isEditorialAdmin()));
        if (!$userCanAddCaseStudy)
            go_to($urlHandler->home());

        $caseStudyRepo = new CaseStudyRepo();
        $caseStudy = $caseStudyRepo->getById($this->caseStudyID);

        if (!$this->userCanModifyCaseStudy($caseStudy, $loggedInUser))
            throw new AccessDenied('You cannot access this page');

        if ($this->format == 'json') {
            try {
                if (isset($_POST['save'])) {
                    $editCaseStudy = new CaseStudyEdit();
                    $editCaseStudy->data()->caseStudyId = $caseStudy->getId();
                    $editCaseStudy->data()->title = $_POST['title'] ?? '';
                    $editCaseStudy->data()->introCardText = $_POST['introCardText'] ?? '';
                    $editCaseStudy->data()->introPageText = $_POST['introPageText'] ?? '';
                    $editCaseStudy->data()->infoText = $_POST['infoText'] ?? '';
                    $editCaseStudy->data()->mainText = $_POST['mainText'] ?? '';
                    $editCaseStudy->data()->projectStartDate = $_POST['projectStartDate'] ?? '';
                    $editCaseStudy->data()->projectEndDate = $_POST['projectEndDate'] ?? '';
                    $editCaseStudy->data()->url = $_POST['url'] ?? '';
                    $editCaseStudy->data()->buttonLabel = $_POST['buttonLabel'] ?? '';
                    $editCaseStudy->data()->cardColour = $_POST['cardColour'] ?? '';
                    $editCaseStudy->data()->isPublished = (bool)$_POST['isPublished'] ?? false;
                    $editCaseStudy->data()->positionOnHomePage = (int)$_POST['positionOnHomePage'] ?? 0;
                    $editCaseStudy->data()->cardBgImage = (string)$_POST['cardImage'] ?? '';
                    $editCaseStudy->data()->projectID = (int)$_POST['projectID'] ?? 0;
                    $editCaseStudy->data()->organisationID = (int)$_POST['organisationID'] ?? 0;
                    $editCaseStudy->exec();

                    echo json_encode([
                        'code' => 'ok',
                        'message' => [
                            'title' => 'Success',
                            'text' => 'Case Study details have been successfully saved',
                        ],
                    ]);
                    return;
                }
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors()
                ]);
                return;
            }

            echo json_encode([
                'title' => $caseStudy->getTitle(),
                'introCardText' => $caseStudy->getIntroCardText(),
                'introPageText' => $caseStudy->getIntroPageText(),
                'infoText' => $caseStudy->getInfoText(),
                'mainText' => $caseStudy->getMainText(),
                'projectStartDate' => $caseStudy->getProjectStartDate(),
                'projectEndDate' => $caseStudy->getProjectEndDate(),
                'url' => $caseStudy->getUrl(),
                'buttonLabel' => $caseStudy->getButtonLabel(),
                'logo' => $caseStudy->getLogo() ?
                    Image::CASE_STUDY_LOGO_URL . $caseStudy->getLogo() : '',
                'cardImage' => $caseStudy->getCardImage() ?
                    Image::CASE_STUDY_CARD_BG_URL . $caseStudy->getCardImage() : '',
                'headerImage' => $caseStudy->getHeaderImage() ?
                    Image::CASE_STUDY_HEADER_URL . $caseStudy->getHeaderImage() : '',
                'cardColour' => $caseStudy->getCardColour(),
                'isPublished' => $caseStudy->isPublished(),
                'isFeaturedOnSlider' => $caseStudy->isFeaturedOnSlider(),
                'positionOnHomePage' => $caseStudy->getPositionOnFirstPage(),
                'projectID' => (string)$caseStudy->getProjectID(),
                'organisationID' => (string)$caseStudy->getOrganisationID(),
                'projects' => array_map(function (Project $project) {
                    return [
                        'id' => $project->getId(),
                        'name' => $project->getName(),
                    ];
                }, (new ProjectRepoInAPC())->getAll()),
                'organisations' => array_map(function (Organisation $organisation) {
                    return [
                        'id' => $organisation->getId(),
                        'name' => $organisation->getName(),
                    ];
                }, (new OrganisationRepoInAPC())->getAll()),
            ]);
            return;

        } else {
            $angularModules['fileUpload'] = true;
            JsModules::setTinyMCE(true);
            require __DIR__ . '/../../../www/views/case-study-edit.php';
        }
    }

    private function userCanModifyCaseStudy(CaseStudy $caseStudy, User $user)
    {
        return true;
    }
}