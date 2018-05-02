<?php

namespace DSI\Controller;

use DSI\Entity\User;
use DSI\Repository\CountryRepo;
use DSI\Repository\EventRepo;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\JsModules;
use Services\URL;
use DSI\UseCase\Events\EventEdit;

class EventEditController
{
    /** @var int */
    public $eventID;

    /** @var string */
    public $format;

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());
        $loggedInUser = $authUser->getUser();

        if (!($this->userCanManageEvent($loggedInUser)))
            go_to($urlHandler->home());

        $event = (new EventRepo())->getById($this->eventID);

        if ($this->format == 'json') {
            if (isset($_POST['save'])) {
                try {
                    $eventEdit = new EventEdit();
                    $eventEdit->data()->event = $event;
                    $eventEdit->data()->title = $_POST['title'] ?? '';
                    $eventEdit->data()->url = $_POST['url'] ?? '';
                    $eventEdit->data()->shortDescription = $_POST['shortDescription'] ?? '';
                    $eventEdit->data()->description = $_POST['description'] ?? '';
                    $eventEdit->data()->startDate = $_POST['startDate'] ?? '';
                    $eventEdit->data()->endDate = $_POST['endDate'] ?? '';
                    $eventEdit->data()->address = $_POST['address'] ?? '';
                    $eventEdit->data()->phoneNumber = $_POST['phoneNumber'] ?? '';
                    $eventEdit->data()->emailAddress = $_POST['emailAddress'] ?? '';
                    $eventEdit->data()->countryID = $_POST['countryID'] ?? '';
                    $eventEdit->data()->region = $_POST['region'] ?? '';
                    $eventEdit->data()->price = $_POST['price'] ?? '';
                    $eventEdit->exec();

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
                return;
            }

            echo json_encode([
                'title' => $event->getTitle(),
                'url' => $event->getUrl(),
                'shortDescription' => $event->getShortDescription(),
                'description' => $event->getDescription(),
                'startDate' => $event->getStartDate(),
                'endDate' => $event->getEndDate(),
                'address' => $event->getAddress(),
                'phoneNumber' => $event->getPhoneNumber(),
                'emailAddress' => $event->getEmailAddress(),
                'price' => $event->getPrice(),
                'countryID' => $event->getCountryID(),
                'region' => $event->getRegionName(),
            ]);
            return;
        }

        $countries = (new CountryRepo())->getAll();
        JsModules::setTinyMCE(true);
        require(__DIR__ . '/../../../www/views/events-edit.php');
    }

    /**
     * @param User $loggedInUser
     * @return bool
     */
    private function userCanManageEvent(User $loggedInUser):bool
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