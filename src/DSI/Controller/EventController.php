<?php

namespace DSI\Controller;

use DSI\Entity\Event;
use DSI\Repository\EventRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

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

        $event = (new EventRepository())->getById($this->eventID);

        if ($this->format == 'json') {

        } else {
            $pageTitle = $event->getTitle() . ' - Digital Social Events';
            require __DIR__ . '/../../../www/views/event.php';
        }
    }

    /**
     * @param Event[] $events
     * @return array
     */
    private function jsonEvents($events)
    {
        return array_map(function (Event $event) {
            return [
                'id' => $event->getId(),
                'title' => $event->getTitle(),
                'url' => $event->getUrl(),
                'shortDescription' => $event->getShortDescription(),
                'description' => $event->getDescription(),
                'startDate' => $event->getStartDate(),
                'startMonth' => $event->getStartDate('Ym'),
                'endDate' => $event->getEndDate(),
                'startDay' => $event->getStartDate('d M Y'),
                'isNew' => $event->isNew(),
                'address' => $event->getAddress(),
                'phoneNumber' => $event->getPhoneNumber(),
                'emailAddress' => $event->getEmailAddress(),
                'price' => $event->getPrice(),
                'countryID' => $event->getCountryID(),
                'region' => $event->getRegionName(),
                'editUrl' => $this->urlHandler->eventEdit($event->getId()),
                'viewUrl' => $this->urlHandler->event($event),
            ];
        }, $events);
    }

    /**
     * @return array
     */
    private function jsonMonths()
    {
        return [
            ['id' => '00', 'title' => '- Before Month -'],
            ['id' => '01', 'title' => 'January'],
            ['id' => '02', 'title' => 'February'],
            ['id' => '03', 'title' => 'March'],
            ['id' => '04', 'title' => 'April'],
            ['id' => '05', 'title' => 'May'],
            ['id' => '06', 'title' => 'June'],
            ['id' => '07', 'title' => 'July'],
            ['id' => '08', 'title' => 'August'],
            ['id' => '09', 'title' => 'September'],
            ['id' => '10', 'title' => 'October'],
            ['id' => '11', 'title' => 'November'],
            ['id' => '12', 'title' => 'December'],
        ];
    }

    /**
     * @param Event[] $events
     * @return array
     */
    private function jsonYearsFromEvents($events)
    {
        $years = [date('Y')];
        foreach ($events AS $event) {
            if ($event->getStartDate('Y'))
                $years[] = $event->getStartDate('Y');
        }
        $years = array_unique($years);
        sort($years);

        $endingYears = array_map(function ($year) {
            return [
                'id' => $year,
                'title' => $year,
            ];
        }, $years);

        array_unshift($endingYears, [
            'id' => '0000',
            'title' => '- Before Year -',
        ]);
        return $endingYears;
    }
}