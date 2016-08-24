<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository\EventRepository;
use \DSI\Repository\CountryRepository;
use \DSI\Entity\Event;
use \DSI\Entity\Country;

class EventRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Repository\CountryRegionRepository */
    private $countryRegionRepository;

    /** @var \DSI\Entity\CountryRegion */
    private $region;

    /** @var CountryRepository */
    private $countryRepository;

    /** @var Country */
    private $country;

    /** @var EventRepository */
    private $eventRepository;

    public function setUp()
    {
        $this->eventRepository = new EventRepository();

        $this->countryRepository = new CountryRepository();
        $this->country = new Country();
        $this->country->setName('Country Name');
        $this->countryRepository->insert($this->country);

        $this->countryRegionRepository = new \DSI\Repository\CountryRegionRepository();
        $this->region = new \DSI\Entity\CountryRegion();
        $this->region->setName('Region Name');
        $this->region->setCountry($this->country);
        $this->countryRegionRepository->insert($this->region);
    }

    public function tearDown()
    {
        $this->countryRepository->clearAll();
        $this->countryRegionRepository->clearAll();
        $this->eventRepository->clearAll();
    }

    /** @test saveAsNew */
    public function eventCanBeCreated()
    {
        $event = new Event();
        $event->setTitle($title = 'Title');
        $event->setUrl($url = 'http://example.org');
        $event->setShortDescription($shortDescription = 'Short Description');
        $event->setDescription($description = 'Description');
        $event->setStartDate($startDate = '2016-10-12');
        $event->setEndDate($endDate = '2016-10-14');
        $event->setRegion($this->region);
        $this->eventRepository->insert($event);

        $this->assertEquals(1, $event->getId());
        $event = $this->eventRepository->getById($event->getId());
        $this->assertEquals($title, $event->getTitle());
        $this->assertEquals($url, $event->getUrl());
        $this->assertEquals($shortDescription, $event->getShortDescription());
        $this->assertEquals($description, $event->getDescription());
        $this->assertEquals($startDate, $event->getStartDate());
        $this->assertEquals($endDate, $event->getEndDate());
        $this->assertEquals($this->region->getId(), $event->getRegionID());

        $this->assertNotEquals('0000-00-00 00:00:00', $event->getTimeCreated());
        $this->assertNotEmpty($event->getTimeCreated());
        $this->assertRegExp('<^\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}:\d{2}$>', $event->getTimeCreated());
    }

    /** @test getByID */
    public function gettingAnNonExistentObjectById_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->eventRepository->getById(1);
    }

    /** @test save */
    public function NonexistentObjectCannotBeSaved()
    {
        $event = new Event();
        $event->setId(1);

        $this->setExpectedException(\DSI\NotFound::class);
        $this->eventRepository->save($event);
    }

    /** @test getAll */
    public function getAll()
    {
        $event = new Event();
        $this->eventRepository->insert($event);

        $this->assertCount(1, $this->eventRepository->getAll());

        $event = new Event();
        $this->eventRepository->insert($event);

        $this->assertCount(2, $this->eventRepository->getAll());
    }

    /** @test */
    public function objectCanBeUpdated()
    {
        $event = new Event();
        $this->eventRepository->insert($event);

        $event->setTitle($title = 'Title');
        $event->setUrl($url = 'http://example.org');
        $event->setShortDescription($shortDescription = 'Short Description');
        $event->setDescription($description = 'Description');
        $event->setStartDate($startDate = '2016-10-12');
        $event->setEndDate($endDate = '2016-10-12');
        $event->setRegion($this->region);
        $this->eventRepository->save($event);

        $event = $this->eventRepository->getById($event->getId());
        $this->assertEquals($title, $event->getTitle());
        $this->assertEquals($url, $event->getUrl());
        $this->assertEquals($shortDescription, $event->getShortDescription());
        $this->assertEquals($description, $event->getDescription());
        $this->assertEquals($startDate, $event->getStartDate());
        $this->assertEquals($endDate, $event->getEndDate());
        $this->assertEquals($this->region->getId(), $event->getRegionID());

        $this->assertNotEquals('0000-00-00 00:00:00', $event->getTimeCreated());
        $this->assertNotEmpty($event->getTimeCreated());
        $this->assertRegExp('<^\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}:\d{2}$>', $event->getTimeCreated());
    }
}