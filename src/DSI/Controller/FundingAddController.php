<?php

namespace DSI\Controller;

use DSI\Entity\User;
use DSI\Repository\CountryRepository;
use DSI\Repository\FundingSourceRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\Funding\FundingAdd;

class FundingAddController
{
    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());
        $loggedInUser = $authUser->getUser();
        
        if (!($this->userCanManageFunding($loggedInUser)))
            go_to($urlHandler->home());

        if (isset($_POST['add'])) {
            try {
                $addFunding = new FundingAdd;
                $addFunding->data()->title = $_POST['title'] ?? '';
                $addFunding->data()->url = $_POST['url'] ?? '';
                $addFunding->data()->description = $_POST['description'] ?? '';
                $addFunding->data()->closingDate = $_POST['closingDate'] ?? '';
                $addFunding->data()->sourceTitle = $_POST['source'] ?? '';
                $addFunding->data()->countryID = $_POST['countryID'] ?? 0;

                $addFunding->exec();

                echo json_encode([
                    'code' => 'ok',
                    'url' => $urlHandler->funding(),
                ]);
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors(),
                ]);
            }
            die();
        }

        $fundingSources = (new FundingSourceRepository())->getAll();
        $countries = (new CountryRepository())->getAll();
        require(__DIR__ . '/../../../www/funding-add.php');
    }

    /**
     * @param User $loggedInUser
     * @return bool
     */
    private function userCanManageFunding(User $loggedInUser):bool
    {
        return (bool)(
            $loggedInUser AND
            (
                $loggedInUser->isCommunityAdmin() OR
                $loggedInUser->isEditorialAdmin()
            )
        );
    }
}