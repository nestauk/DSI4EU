<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository\CountryRegionRepository;
use \DSI\Repository\CountryRepository;
use \DSI\Entity\Country;

class CountryRegionRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var CountryRegionRepository */
    protected $countryRegionRepo;

    /** @var CountryRepository */
    protected $countryRepo;

    /** @var Country */
    protected $country_1, $country_2;

    public function setUp()
    {
        $this->countryRegionRepo = new CountryRegionRepository();
        $this->countryRepo = new CountryRepository();

        $this->country_1 = new Country();
        $this->country_1->setName('Romania');
        $this->countryRepo->saveAsNew($this->country_1);

        $this->country_2 = new Country();
        $this->country_2->setName('UK');
        $this->countryRepo->saveAsNew($this->country_2);
    }

    public function tearDown()
    {
        $this->countryRegionRepo->clearAll();
        $this->countryRepo->clearAll();
    }

    /** @test saveAsNew */
    public function countryRegionCanBeSaved()
    {
        $countryRegion = new \DSI\Entity\CountryRegion();
        $countryRegion->setName('test');
        $countryRegion->setCountry($this->country_1);

        $this->countryRegionRepo->insert($countryRegion);

        $this->assertEquals(1, $countryRegion->getId());
    }

    /** @test save, getByID */
    public function countryRegionCanBeUpdated()
    {
        $countryRegion = new \DSI\Entity\CountryRegion();
        $countryRegion->setName('test');
        $countryRegion->setCountry($this->country_1);

        $this->countryRegionRepo->insert($countryRegion);

        $countryRegion->setName('test2');
        $countryRegion->setCountry($this->country_2);
        $this->countryRegionRepo->save($countryRegion);

        $sameCountryRegion = $this->countryRegionRepo->getById($countryRegion->getId());
        $this->assertEquals($countryRegion->getName(), $sameCountryRegion->getName());
        $this->assertEquals($countryRegion->getCountry()->getId(), $sameCountryRegion->getCountry()->getId());
    }

    /** @test getByID */
    public function gettingAnNonExistentCountryRegionById_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->countryRegionRepo->getById(1);
    }

    /** @test save */
    public function nonexistentCountryRegionCannotBeSaved()
    {
        $name = 'test';
        $countryRegion = new \DSI\Entity\CountryRegion();
        $countryRegion->setId(1);
        $countryRegion->setCountry($this->country_1);
        $countryRegion->setName($name);

        $this->setExpectedException(\DSI\NotFound::class);
        $this->countryRegionRepo->save($countryRegion);
    }

    /** @test nameExists */
    public function NonexistentCountryRegionCannotBeFoundByCountryAndName()
    {
        $this->assertFalse($this->countryRegionRepo->nameExists(1, 'test'));
    }

    /** @test saveAsNew */
    public function cannotSaveAsNewWithoutAName()
    {
        $countryRegion = new \DSI\Entity\CountryRegion();
        $countryRegion->setCountry($this->country_1);
        $this->setExpectedException(\DSI\NotEnoughData::class);
        $this->countryRegionRepo->insert($countryRegion);
    }

    /** @test saveAsNew */
    public function cannotSaveAsNewWithoutACountry()
    {
        $countryRegion = new \DSI\Entity\CountryRegion();
        $countryRegion->setName('test');
        $this->setExpectedException(\DSI\NotEnoughData::class);
        $this->countryRegionRepo->insert($countryRegion);
    }

    /** @test save */
    public function cannotSaveWithAnEmptyName()
    {
        $countryRegion = new \DSI\Entity\CountryRegion();
        $countryRegion->setCountry($this->country_1);
        $countryRegion->setName('test');
        $this->countryRegionRepo->insert($countryRegion);

        $countryRegion->setName('');
        $this->setExpectedException(\DSI\NotEnoughData::class);
        $this->countryRegionRepo->save($countryRegion);
    }

    /** @test save */
    public function cannotSaveAsNew2CountryRegionsWithTheSameName()
    {
        $countryRegion = new \DSI\Entity\CountryRegion();
        $countryRegion->setCountry($this->country_1);
        $countryRegion->setName('test');
        $this->countryRegionRepo->insert($countryRegion);

        $countryRegion = new \DSI\Entity\CountryRegion();
        $countryRegion->setCountry($this->country_1);
        $countryRegion->setName('test');
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->countryRegionRepo->insert($countryRegion);
    }

    /** @test save */
    public function cannotSave2CountriesWithTheSameName()
    {
        $countryRegion_1 = new \DSI\Entity\CountryRegion();
        $countryRegion_1->setCountry($this->country_1);
        $countryRegion_1->setName('test');
        $this->countryRegionRepo->insert($countryRegion_1);

        $countryRegion_2 = new \DSI\Entity\CountryRegion();
        $countryRegion_2->setCountry($this->country_1);
        $countryRegion_2->setName('test2');
        $this->countryRegionRepo->insert($countryRegion_2);

        $countryRegion_2->setName('test');
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->countryRegionRepo->save($countryRegion_2);
    }

    /** @test getAll */
    public function getAllCountryRegions()
    {
        $countryRegion = new \DSI\Entity\CountryRegion();
        $countryRegion->setCountry($this->country_1);
        $countryRegion->setName('test');
        $this->countryRegionRepo->insert($countryRegion);

        $this->assertCount(1, $this->countryRegionRepo->getAll());

        $countryRegion = new \DSI\Entity\CountryRegion();
        $countryRegion->setCountry($this->country_1);
        $countryRegion->setName('test2');
        $this->countryRegionRepo->insert($countryRegion);

        $this->assertCount(2, $this->countryRegionRepo->getAll());
    }

    /** @test saveAsNew getById */
    public function setAllCountryRegionsDetails()
    {
        $countryRegion = new \DSI\Entity\CountryRegion();
        $countryRegion->setCountry($this->country_1);
        $countryRegion->setName('test');
        $this->countryRegionRepo->insert($countryRegion);

        $sameCountryRegion = $this->countryRegionRepo->getById($countryRegion->getId());
        $this->assertEquals($countryRegion->getName(), $sameCountryRegion->getName());
        $this->assertEquals($countryRegion->getCountry()->getId(), $sameCountryRegion->getCountry()->getId());
    }
}