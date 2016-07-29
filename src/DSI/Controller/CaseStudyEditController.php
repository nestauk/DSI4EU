<?php

namespace DSI\Controller;

use DSI\AccessDenied;
use DSI\Entity\Image;
use DSI\Entity\CaseStudy;
use DSI\Entity\User;
use DSI\Repository\CaseStudyRepository;
use DSI\Repository\CountryRegionRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\EditCaseStudy;

class CaseStudyEditController
{
    public $caseStudyID;
    public $format = 'html';

    public function exec()
    {
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo(URL::login());
        $loggedInUser = (new UserRepository())->getById($authUser->getUserId());

        $userCanAddCaseStudy = (bool)($loggedInUser AND ($loggedInUser->isCommunityAdmin() OR $loggedInUser->isEditorialAdmin()));
        if (!$userCanAddCaseStudy)
            go_to(URL::home());

        $caseStudyRepo = new CaseStudyRepository();
        $caseStudy = $caseStudyRepo->getById($this->caseStudyID);

        if (!$this->userCanModifyCaseStudy($caseStudy, $loggedInUser))
            throw new AccessDenied('You cannot access this page');

        if ($this->format == 'json') {
            try {
                if (isset($_POST['save'])) {
                    $editCaseStudy = new EditCaseStudy();
                    $editCaseStudy->data()->caseStudyId = $caseStudy->getId();
                    $editCaseStudy->data()->title = $_POST['title'] ?? '';
                    $editCaseStudy->data()->introCardText = $_POST['introCardText'] ?? '';
                    $editCaseStudy->data()->introPageText = $_POST['introPageText'] ?? '';
                    $editCaseStudy->data()->mainText = $_POST['mainText'] ?? '';
                    $editCaseStudy->data()->projectStartDate = $_POST['projectStartDate'] ?? '';
                    $editCaseStudy->data()->projectEndDate = $_POST['projectEndDate'] ?? '';
                    $editCaseStudy->data()->url = $_POST['url'] ?? '';
                    $editCaseStudy->data()->buttonLabel = $_POST['buttonLabel'] ?? '';
                    $editCaseStudy->data()->cardColour = $_POST['cardColour'] ?? '';
                    $editCaseStudy->data()->isPublished = $_POST['isPublished'] ?? false;
                    $editCaseStudy->data()->isFeaturedOnSlider = $_POST['isFeaturedOnSlider'] ?? false;
                    $editCaseStudy->data()->isFeaturedOnHomePage = $_POST['isFeaturedOnHomePage'] ?? false;

                    $editCaseStudy->data()->logoImage = $_POST['logo'] ?? '';
                    $editCaseStudy->data()->cardBgImage = $_POST['cardImage'] ?? '';
                    $editCaseStudy->data()->headerImage = $_POST['headerImage'] ?? '';

                    $editCaseStudy->data()->countryID = $_POST['countryID'] ?? '';
                    $editCaseStudy->data()->region = $_POST['region'] ?? '';

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
                'isFeaturedOnHomePage' => $caseStudy->isFeaturedOnHomePage(),
                'countryID' => $caseStudy->getCountryId(),
                'regionID' => $caseStudy->getRegionID(),
                'region' => $caseStudy->getRegionName(),
            ]);
            return;

        } else {
            $angularModules['fileUpload'] = true;
            require __DIR__ . '/../../../www/case-study-edit.php';
        }
    }

    private function userCanModifyCaseStudy(CaseStudy $caseStudy, User $user)
    {
        return true;
    }
}