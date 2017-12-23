<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository\EventRepo;
use \DSI\Repository\CountryRepo;
use \DSI\Entity\Event;
use \DSI\Entity\Country;

class EventRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Repository\CountryRegionRepo */
    private $countryRegionRepository;

    /** @var \DSI\Entity\CountryRegion */
    private $region;

    /** @var CountryRepo */
    private $countryRepository;

    /** @var Country */
    private $country;

    /** @var EventRepo */
    private $eventRepository;

    public function setUp()
    {
        $this->eventRepository = new EventRepo();

        $this->countryRepository = new CountryRepo();
        $this->country = new Country();
        $this->country->setName('Country Name');
        $this->countryRepository->insert($this->country);

        $this->countryRegionRepository = new \DSI\Repository\CountryRegionRepo();
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
        $event->setAddress($address = 'Elms Crescent, London');
        $event->setPhoneNumber($phoneNumber = '01234 567 890');
        $event->setEmailAddress($emailAddress = 'alecs@example.org');
        $event->setPrice($price = 'Free');
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
        $this->assertEquals($address, $event->getAddress());
        $this->assertEquals($phoneNumber, $event->getPhoneNumber());
        $this->assertEquals($emailAddress, $event->getEmailAddress());
        $this->assertEquals($price, $event->getPrice());
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

    /** @test getAll */
    public function getFutureOnes()
    {
        $_days = 60 * 60 * 24;

        $event = new Event();
        $event->setEndDate(date('Y-m-d', time() + 30 * $_days));
        $this->eventRepository->insert($event);

        $this->assertCount(1, $this->eventRepository->getAll());

        $event = new Event();
        $event->setEndDate(date('Y-m-d', time() - 30 * $_days));
        $this->eventRepository->insert($event);

        $this->assertCount(1, $this->eventRepository->getFutureOnes());
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
        $event->setAddress($address = 'Elms Crescent, London');
        $event->setPhoneNumber($phoneNumber = '01234 567 890');
        $event->setEmailAddress($emailAddress = 'alecs@example.org');
        $event->setPrice($price = 'Free');
        $event->setRegion($this->region);
        $this->eventRepository->save($event);

        $event = $this->eventRepository->getById($event->getId());
        $this->assertEquals($title, $event->getTitle());
        $this->assertEquals($url, $event->getUrl());
        $this->assertEquals($shortDescription, $event->getShortDescription());
        $this->assertEquals($description, $event->getDescription());
        $this->assertEquals($startDate, $event->getStartDate());
        $this->assertEquals($endDate, $event->getEndDate());
        $this->assertEquals($address, $event->getAddress());
        $this->assertEquals($phoneNumber, $event->getPhoneNumber());
        $this->assertEquals($emailAddress, $event->getEmailAddress());
        $this->assertEquals($price, $event->getPrice());
        $this->assertEquals($this->region->getId(), $event->getRegionID());

        $this->assertNotEquals('0000-00-00 00:00:00', $event->getTimeCreated());
        $this->assertNotEmpty($event->getTimeCreated());
        $this->assertRegExp('<^\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}:\d{2}$>', $event->getTimeCreated());
    }
}