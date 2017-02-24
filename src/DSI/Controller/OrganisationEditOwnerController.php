<?php

namespace DSI\Controller;

use DSI\Repository\OrganisationRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\Organisations\ChangeOwner;

class OrganisationEditOwnerController
{
    /** @var  int */
    public $organisationID;

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());
        $loggedInUser = $authUser->getUser();

        $organisationRepo = new OrganisationRepository();
        $organisation = $organisationRepo->getById($this->organisationID);
        $owner = $organisation->getOwner();

        if ($owner->getId() == $loggedInUser->getId())
            $isOwner = true;
        else
            $isOwner = false;

        if (!$isOwner AND !$loggedInUser->isSysAdmin())
            go_to($urlHandler->home());

        if (isset($_POST['save'])) {
            try {
                $exec = new ChangeOwner();
                $exec->data()->executor = $loggedInUser;
                $exec->data()->member = (new UserRepository())->getById($_POST['newOwnerID']);
                $exec->data()->organisation = $organisation;
                $exec->exec();

                echo json_encode([
                    'code' => 'ok',
                    'url' => $urlHandler->organisation($organisation),
                ]);
            } catch (ErrorHandler $e){
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors(),
                ]);
            }
            return;
        }

        $pageTitle = $organisation->getName();
        $users = (new UserRepository())->getAll();
        require __DIR__ . '/../../../www/views/organisation-edit-owner.php';
    }
}