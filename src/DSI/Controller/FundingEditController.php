<?php

namespace DSI\Controller;

use DSI\Repository\CountryRepository;
use DSI\Repository\FundingRepository;
use DSI\Repository\FundingSourceRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\AddFunding;

class FundingEditController
{
    /** @var int */
    public $fundingID;

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());

        $loggedInUser = (new UserRepository())->getById($authUser->getUserId());
        $userCanEditFunding = (bool)($loggedInUser AND ($loggedInUser->isCommunityAdmin() OR $loggedInUser->isEditorialAdmin()));
        if (!$userCanEditFunding)
            go_to($urlHandler->home());

        $funding = (new FundingRepository())->getById($this->fundingID);

        if (isset($_POST['save'])) {
            try {
                $addFunding = new AddFunding;
                $addFunding->data()->title = $_POST['title'] ?? '';
                $addFunding->data()->url = $_POST['url'] ?? '';
                $addFunding->data()->description = $_POST['description'] ?? '';
                $addFunding->data()->closingDate = $_POST['closingDate'] ?? '';
                $addFunding->data()->fundingSource = $_POST['source'] ?? '';
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
        require(__DIR__ . '/../../../www/funding-edit.php');
    }
}