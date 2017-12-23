<?php

namespace DSI\UseCase\Events;

use DSI\Entity\CountryRegion;
use DSI\Entity\Event;
use DSI\Repository\CountryRegionRepo;
use DSI\Repository\EventRepo;
use DSI\Service\ErrorHandler;
use DSI\UseCase\CreateCountryRegion;

class EventEdit
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var EventEdit_Data */
    private $data;

    /** @var EventRepo */
    private $eventRepository;

    /** @var CountryRegionRepo */
    private $countryRegionRepo;

    /** @var CountryRegion */
    private $countryRegion;

    public function __construct()
    {
        $this->data = new EventEdit_Data();
        $this->countryRegionRepo = new CountryRegionRepo();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->eventRepository = new EventRepo();

        $this->assertDataHasBeenSubmitted();
        $this->assertDataIsNotEmpty();
        $this->assertDatesAreValid();
        $this->getRegion();
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
        if ($this->countryRegion)
            $this->data()->event->setRegion($this->countryRegion);

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

    private function getRegion()
    {
        if ($this->data()->countryID != 0) {
            if ($this->countryRegionRepo->nameExists($this->data()->countryID, $this->data()->region)) {
                $countryRegion = $this->countryRegionRepo->getByName($this->data()->countryID, $this->data()->region);
            } else {
                $createCountryRegionCmd = new CreateCountryRegion();
                $createCountryRegionCmd->data()->countryID = $this->data()->countryID;
                $createCountryRegionCmd->data()->name = $this->data()->region;
                $createCountryRegionCmd->exec();
                $countryRegion = $createCountryRegionCmd->getCountryRegion();
            }
            $this->countryRegion = $countryRegion;
        }
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
        $price,
        $region;

    /** @var int */
    public $countryID;
}