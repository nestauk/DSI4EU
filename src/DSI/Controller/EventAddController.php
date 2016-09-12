<?php

namespace DSI\Controller;

use DSI\Entity\User;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\Events\EventAdd;

class EventAddController
{
    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());
        $loggedInUser = $authUser->getUser();

        if (!($this->userCanManageEvents($loggedInUser)))
            go_to($urlHandler->home());

        if (isset($_POST['add'])) {
            try {
                $eventAdd = new EventAdd();
                $eventAdd->data()->title = $_POST['title'] ?? '';
                $eventAdd->data()->url = $_POST['url'] ?? '';
                $eventAdd->data()->shortDescription = $_POST['shortDescription'] ?? '';
                $eventAdd->data()->description = $_POST['description'] ?? '';
                $eventAdd->data()->startDate = $_POST['startDate'] ?? '';
                $eventAdd->data()->endDate = $_POST['endDate'] ?? '';

                $eventAdd->exec();

                echo json_encode([
                    'code' => 'ok',
                    'url' => $urlHandler->events(),
                ]);
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors(),
                ]);
            }
            die();
        }

        require(__DIR__ . '/../../../www/views/events-add.php');
    }

    /**
     * @param User $loggedInUser
     * @return bool
     */
    private function userCanManageEvents(User $loggedInUser):bool
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