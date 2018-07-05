<?php

namespace DSI\Controller;

use DSI\Entity\User;
use DSI\Repository\EventRepo;
use DSI\Service\Auth;
use Services\URL;

class EventController
{
    /** @var int */
    public $eventID;

    public $format = 'html';

    /** @var URL */
    private $urlHandler;

    public function exec()
    {
        $this->urlHandler = $urlHandler = new URL();
        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();

        $event = (new EventRepo())->getById($this->eventID);
        $userCanManageEvent = $this->userCanManageEvents($loggedInUser);

        if ($this->format == 'json') {

        } else {
            \Services\View::setPageTitle($event->getTitle() . ' - Digital Social Events');
            require __DIR__ . '/../../../www/views/event.php';
        }
    }

    /**
     * @param User $loggedInUser
     * @return bool
     */
    private function userCanManageEvents($loggedInUser):bool
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