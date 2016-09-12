<?php

namespace DSI\UseCase\Events;

use DSI\Entity\Event;
use DSI\NotFound;
use DSI\Repository\EventRepository;
use DSI\Service\ErrorHandler;

class EventAdd
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var EventAdd_Data */
    private $data;

    /** @var EventRepository */
    private $eventRepository;

    public function __construct()
    {
        $this->data = new EventAdd_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->eventRepository = new EventRepository();

        $this->assertDataHasBeenSubmitted();
        $this->assertDataIsNotEmpty();
        $this->assertDatesAreValid();
        $this->saveEvent();
    }

    /**
     * @return EventAdd_Data
     */
    public function data()
    {
        return $this->data;
    }

    private function saveEvent()
    {
        $event = new Event();
        $event->setTitle($this->data()->title);
        $event->setUrl($this->data()->url);
        $event->setShortDescription($this->data()->shortDescription);
        $event->setDescription($this->data()->description);
        $event->setStartDate($this->data()->startDate);
        $event->setEndDate($this->data()->endDate);

        $this->eventRepository->insert($event);
    }

    private function assertDataHasBeenSubmitted()
    {

    }

    private function assertDataIsNotEmpty()
    {
        if (!$this->data()->title OR $this->data()->title == '')
            $this->errorHandler->addTaggedError('title', 'Please specify a title');
        if (!$this->data()->url OR $this->data()->url == '')
            $this->errorHandler->addTaggedError('url', 'Please specify the url');
        if (!$this->data()->shortDescription OR $this->data()->shortDescription == '')
            $this->errorHandler->addTaggedError('shortDescription', 'Please write a short description');
        if (!$this->data()->description OR $this->data()->description == '')
            $this->errorHandler->addTaggedError('description', 'Please write a description');

        $this->errorHandler->throwIfNotEmpty();
    }

    private function assertDatesAreValid()
    {
        if ($this->data()->startDate AND $this->data()->startDate != '')
            if (!preg_match('<^\d{4}\-\d{2}\-\d{2}$>', $this->data()->startDate))
                $this->errorHandler->addTaggedError('startDate', 'Please specify a valid start date');

        if ($this->data()->endDate AND $this->data()->endDate != '')
            if (!preg_match('<^\d{4}\-\d{2}\-\d{2}$>', $this->data()->endDate))
                $this->errorHandler->addTaggedError('endDate', 'Please specify a valid end date');

        $this->errorHandler->throwIfNotEmpty();
    }
}

class EventAdd_Data
{
    /** @var string */
    public $title,
        $url,
        $shortDescription,
        $description;

    /** @var string */
    public $startDate,
        $endDate;
}