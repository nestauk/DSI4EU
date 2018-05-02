<?php

namespace DSI\Controller;

use DSI\Entity\User;
use DSI\Repository\CountryRepo;
use DSI\Repository\FundingRepo;
use DSI\Repository\FundingSourceRepo;
use DSI\Repository\FundingTargetRepo;
use DSI\Repository\FundingTypeRepo;
use DSI\Repository\UserRepo;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\JsModules;
use Services\URL;
use DSI\UseCase\Funding\FundingEdit;

class FundingEditController
{
    /** @var int */
    public $fundingID;

    /** @var string */
    public $format;

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());
        $loggedInUser = $authUser->getUser();

        if (!($this->userCanManageFunding($loggedInUser)))
            go_to($urlHandler->home());

        $funding = (new FundingRepo())->getById($this->fundingID);

        if ($this->format == 'json') {
            if (isset($_POST['save'])) {
                try {
                    $editFunding = new FundingEdit();
                    $editFunding->data()->funding = $funding;
                    $editFunding->data()->title = $_POST['title'] ?? '';
                    $editFunding->data()->url = $_POST['url'] ?? '';
                    $editFunding->data()->description = $_POST['description'] ?? '';
                    $editFunding->data()->closingDate = $_POST['closingDate'] ?? '';
                    $editFunding->data()->typeID = (int)($_POST['typeID'] ?? 0);
                    $editFunding->data()->targets = (array)($_POST['targets'] ?? []);
                    $editFunding->data()->sourceTitle = $_POST['source'] ?? '';
                    $editFunding->data()->countryID = $_POST['countryID'] ?? 0;

                    $editFunding->exec();

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
                return;
            }

            echo json_encode([
                'title' => $funding->getTitle(),
                'url' => $funding->getUrl(),
                'description' => $funding->getDescription(),
                'closingDate' => $funding->getClosingDate(),
                'source' => $funding->getSourceTitle(),
                'countryID' => $funding->getCountryID(),
                'typeID' => $funding->getTypeID(),
                'targets' => $funding->getTargetIDs(),
            ]);
            return;
        }

        $fundingTypes = (new FundingTypeRepo())->getAll();
        $fundingTargets = (new FundingTargetRepo())->getAll();
        $fundingSources = (new FundingSourceRepo())->getAll();
        $countries = (new CountryRepo())->getAll();
        JsModules::setTinyMCE(true);
        require(__DIR__ . '/../../../www/views/funding-edit.php');
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