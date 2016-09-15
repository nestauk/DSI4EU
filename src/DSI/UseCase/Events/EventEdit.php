<?php

namespace DSI\UseCase\Events;

use DSI\Entity\Event;
use DSI\Repository\CountryRepository;
use DSI\Repository\EventRepository;
use DSI\Service\ErrorHandler;

class EventEdit
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var EventEdit_Data */
    private $data;

    /** @var EventRepository */
    private $eventRepository;

    public function __construct()
    {
        $this->data = new EventEdit_Data();
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
     * @return EventEdit_Data
     */
    public function data()
    {
        return $this->data;
    }

    private function saveEvent()
    {
        $this->data()->event->setTitle($this->data()->title);
        $this->data()->event->setUrl($this->data()->url);
        $this->data()->event->setShortDescription($this->data()->shortDescription);
        $this->data()->event->setDescription($this->data()->description);
        $this->data()->event->setStartDate($this->data()->startDate);
        $this->data()->event->setEndDate($this->data()->endDate);
        $this->data()->event->setAddress($this->data()->address);
        $this->data()->event->setPhoneNumber($this->data()->phoneNumber);
        $this->data()->event->setEmailAddress($this->data()->emailAddress);
        $this->data()->event->setPrice($this->data()->price);

        $this->eventRepository->save($this->data()->event);
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

class EventEdit_Data
{
    /** @var Event */
    public $event;

    /** @var string */
    public $title,
        $url,
        $shortDescription,
        $description,
        $startDate,
        $endDate,
        $address,
        $phoneNumber,
        $emailAddress,
        $price;
}