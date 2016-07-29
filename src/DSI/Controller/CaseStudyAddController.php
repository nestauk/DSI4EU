<?php

namespace DSI\Controller;

use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\CreateCaseStudy;

class CaseStudyAddController
{
    public function exec()
    {
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo(URL::login());

        $loggedInUser = (new UserRepository())->getById($authUser->getUserId());
        $userCanAddCaseStudy = (bool)($loggedInUser AND ($loggedInUser->isCommunityAdmin() OR $loggedInUser->isEditorialAdmin()));
        if (!$userCanAddCaseStudy)
            go_to(URL::home());

        if (isset($_POST['add'])) {
            try {
                $createCaseStudy = new CreateCaseStudy();
                $createCaseStudy->data()->title = $_POST['title'] ?? '';
                $createCaseStudy->data()->introCardText = $_POST['introCardText'] ?? '';
                $createCaseStudy->data()->introPageText = $_POST['introPageText'] ?? '';
                $createCaseStudy->data()->mainText = $_POST['mainText'] ?? '';
                $createCaseStudy->data()->projectStartDate = $_POST['projectStartDate'] ?? '';
                $createCaseStudy->data()->projectEndDate = $_POST['projectEndDate'] ?? '';
                $createCaseStudy->data()->url = $_POST['url'] ?? '';
                $createCaseStudy->data()->buttonLabel = $_POST['buttonLabel'] ?? '';
                $createCaseStudy->data()->cardColour = $_POST['cardColour'] ?? '';
                $createCaseStudy->data()->isPublished = $_POST['isPublished'] ?? '';

                $createCaseStudy->data()->logoImage = $_POST['logo'] ?? '';
                $createCaseStudy->data()->cardBgImage = $_POST['cardImage'] ?? '';
                $createCaseStudy->data()->headerImage = $_POST['headerImage'] ?? '';

                $createCaseStudy->data()->countryID = $_POST['countryID'] ?? '';
                $createCaseStudy->data()->region = $_POST['region'] ?? '';

                $createCaseStudy->exec();
                $caseStudy = $createCaseStudy->getCaseStudy();

                echo json_encode([
                    'code' => 'ok',
                    'url' => URL::caseStudy($caseStudy),
                ]);
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors(),
                ]);
            }
            die();
        }

        $angularModules['fileUpload'] = true;
        require(__DIR__ . '/../../../www/case-study-add.php');
    }
}