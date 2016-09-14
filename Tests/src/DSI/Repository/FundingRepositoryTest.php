<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository\FundingSourceRepository;
use \DSI\Repository\FundingRepository;
use \DSI\Repository\CountryRepository;
use \DSI\Entity\FundingSource;
use \DSI\Entity\Funding;
use \DSI\Entity\Country;

class FundingRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var FundingSourceRepository */
    private $fundingSourceRepository;

    /** @var FundingSource */
    private $fundingSource;

    /** @var CountryRepository */
    private $countryRepository;

    /** @var Country */
    private $country;

    /** @var FundingRepository */
    private $fundingRepository;

    public function setUp()
    {
        $this->fundingRepository = new FundingRepository();

        $this->fundingSourceRepository = new FundingSourceRepository();
        $this->fundingSource = new FundingSource();
        $this->fundingSourceRepository->insert($this->fundingSource);

        $this->countryRepository = new CountryRepository();
        $this->country = new Country();
        $this->country->setName('Country Name');
        $this->countryRepository->insert($this->country);
    }

    public function tearDown()
    {
        $this->fundingSourceRepository->clearAll();
        $this->countryRepository->clearAll();
        $this->fundingRepository->clearAll();
    }

    /** @test saveAsNew */
    public function fundingCanBeCreated()
    {
        $funding = new Funding();
        $funding->setTitle($title = 'Title');
        $funding->setUrl($url = 'http://example.org');
        $funding->setDescription($description = 'Description');
        $funding->setClosingDate($closingDate = '2016-10-12');
        $funding->setSource($this->fundingSource);
        $funding->setCountry($this->country);
        $this->fundingRepository->insert($funding);

        $this->assertEquals(1, $funding->getId());
        $funding = $this->fundingRepository->getById($funding->getId());
        $this->assertEquals($title, $funding->getTitle());
        $this->assertEquals($url, $funding->getUrl());
        $this->assertEquals($description, $funding->getDescription());
        $this->assertEquals($closingDate, $funding->getClosingDate());
        $this->assertEquals($this->fundingSource->getId(), $funding->getSourceID());
        $this->assertEquals($this->country->getId(), $funding->getCountryID());

        $this->assertNotEquals('0000-00-00 00:00:00', $funding->getTimeCreated());
        $this->assertNotEmpty($funding->getTimeCreated());
        $this->assertRegExp('<^\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}:\d{2}$>', $funding->getTimeCreated());
    }

    /** @test getByID */
    public function gettingAnNonExistentObjectById_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->fundingRepository->getById(1);
    }

    /** @test save */
    public function NonexistentObjectCannotBeSaved()
    {
        $funding = new Funding();
        $funding->setId(1);

        $this->setExpectedException(\DSI\NotFound::class);
        $this->fundingRepository->save($funding);
    }

    /** @test getAll */
    public function getAll()
    {
        $funding = new Funding();
        $this->fundingRepository->insert($funding);

        $this->assertCount(1, $this->fundingRepository->getAll());

        $funding = new Funding();
        $this->fundingRepository->insert($funding);

        $this->assertCount(2, $this->fundingRepository->getAll());
    }

    /** @test getAll */
    public function getFutureOnes()
    {
        $days = 60 * 60 * 24;
        $funding = new Funding();
        $funding->setClosingDate(date('Y-m-d', time() + 30 * $days));
        $this->fundingRepository->insert($funding);

        $funding = new Funding();
        $funding->setClosingDate(date('Y-m-d', time() - 30 * $days));
        $this->fundingRepository->insert($funding);

        $this->assertCount(1, $this->fundingRepository->getFutureOnes());
    }

    /** @test */
    public function objectCanBeUpdated()
    {
        $funding = new Funding();
        $this->fundingRepository->insert($funding);

        $funding->setTitle($title = 'Title');
        $funding->setUrl($url = 'http://example.org');
        $funding->setDescription($description = 'Description');
        $funding->setClosingDate($closingDate = '2016-10-12');
        $funding->setSource($this->fundingSource);
        $funding->setCountry($this->country);
        $this->fundingRepository->save($funding);

        $funding = $this->fundingRepository->getById($funding->getId());
        $this->assertEquals($title, $funding->getTitle());
        $this->assertEquals($url, $funding->getUrl());
        $this->assertEquals($description, $funding->getDescription());
        $this->assertEquals($closingDate, $funding->getClosingDate());
        $this->assertEquals($this->fundingSource->getId(), $funding->getSourceID());
        $this->assertEquals($this->country->getId(), $funding->getCountryID());

        $this->assertNotEquals('0000-00-00 00:00:00', $funding->getTimeCreated());
        $this->assertNotEmpty($funding->getTimeCreated());
        $this->assertRegExp('<^\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}:\d{2}$>', $funding->getTimeCreated());
    }
}