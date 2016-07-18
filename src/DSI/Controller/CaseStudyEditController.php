<?php

namespace DSI\Controller;

use DSI\AccessDenied;
use DSI\Entity\Image;
use DSI\Entity\CaseStudy;
use DSI\Entity\User;
use DSI\Repository\CaseStudyRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;

class CaseStudyEditController
{
    public $caseStudyID;
    public $format = 'html';

    public function exec()
    {
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo(URL::login());
        $loggedInUser = (new UserRepository())->getById($authUser->getUserId());

        $caseStudyRepo = new CaseStudyRepository();
        $caseStudy = $caseStudyRepo->getById($this->caseStudyID);

        if (!$this->userCanModifyCaseStudy($caseStudy, $loggedInUser))
            throw new AccessDenied('You cannot access this page');

        if ($this->format == 'json') {
            try {
                if (isset($_POST['saveDetails'])) {
                    $updateProject = new UpdateProject();
                    $updateProject->data()->project = $caseStudy;
                    $updateProject->data()->user = $loggedInUser;
                    if (isset($_POST['name']))
                        $updateProject->data()->name = $_POST['name'];
                    if (isset($_POST['url']))
                        $updateProject->data()->url = $_POST['url'];
                    if (isset($_POST['status']))
                        $updateProject->data()->status = $_POST['status'];
                    if (isset($_POST['description']))
                        $updateProject->data()->description = $_POST['description'];

                    $updateProject->data()->startDate = $_POST['startDate'] ?? NULL;
                    $updateProject->data()->endDate = $_POST['endDate'] ?? NULL;
                    $updateProject->exec();

                    $updateProjectCountryRegionCmd = new UpdateProjectCountryRegion();
                    $updateProjectCountryRegionCmd->data()->projectID = $caseStudy->getId();
                    $updateProjectCountryRegionCmd->data()->countryID = $_POST['countryID'] ?? '';
                    $updateProjectCountryRegionCmd->data()->region = $_POST['region'] ?? '';
                    $updateProjectCountryRegionCmd->exec();

                    if ($_POST['logo'] != Image::PROJECT_LOGO_URL . $caseStudy->getLogoOrDefault()) {
                        $updateProjectLogo = new UpdateProjectLogo();
                        $updateProjectLogo->data()->projectID = $caseStudy->getId();
                        $updateProjectLogo->data()->fileName = basename($_POST['logo']);
                        $updateProjectLogo->exec();
                    }

                    echo json_encode([
                        'result' => 'ok',
                        'message' => [
                            'title' => 'Success',
                            'text' => 'Project Details have been successfully saved',
                        ],
                    ]);
                    return;
                }
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'result' => 'error',
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
                'logo' => $caseStudy->getLogo(),
                'cardImage' => $caseStudy->getCardImage(),
                'headerImage' => $caseStudy->getHeaderImage(),
                'cardColour' => $caseStudy->getCardColour(),
                'isPublished' => $caseStudy->isPublished(),
                'regionID' => $caseStudy->getRegionID(),
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