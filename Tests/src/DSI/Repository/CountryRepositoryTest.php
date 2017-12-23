<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository\CountryRepo;
use \DSI\Entity\Country;

class CountryRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var CountryRepo */
    protected $countryRepo;

    public function setUp()
    {
        $this->countryRepo = new CountryRepo();
    }

    public function tearDown()
    {
        $this->countryRepo->clearAll();
    }

    /** @test saveAsNew */
    public function countryCanBeSaved()
    {
        $country = new Country();
        $country->setName('test');

        $this->countryRepo->insert($country);

        $this->assertEquals(1, $country->getId());
    }

    /** @test save, getByID */
    public function countryCanBeUpdated()
    {
        $country = new Country();
        $country->setName('test');

        $this->countryRepo->insert($country);

        $country->setName('test2');
        $this->countryRepo->save($country);

        $sameCountry = $this->countryRepo->getById($country->getId());
        $this->assertEquals($country->getName(), $sameCountry->getName());
    }

    /** @test getByID */
    public function gettingAnNonExistentCountryById_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->countryRepo->getById(1);
    }

    /** @test save */
    public function nonexistentCountryCannotBeSaved()
    {
        $name = 'test';
        $country = new Country();
        $country->setId(1);
        $country->setName($name);

        $this->setExpectedException(\DSI\NotFound::class);
        $this->countryRepo->save($country);
    }

    /** @test getByName */
    public function countryCanBeRetrievedByName()
    {
        $name = 'test';
        $country = new Country();
        $country->setName($name);
        $this->countryRepo->insert($country);

        $sameTag = $this->countryRepo->getByName($name);
        $this->assertEquals($country->getId(), $sameTag->getId());
    }

    /** @test getByID */
    public function gettingAnNonExistentCountryByName_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->countryRepo->getByName('test');
    }

    /** @test nameExists */
    public function NonexistentCountryCannotBeFoundByName()
    {
        $this->assertFalse($this->countryRepo->nameExists('test'));
    }

    /** @test saveAsNew */
    public function cannotSaveAsNewWithoutAName()
    {
        $country = new Country();
        $this->setExpectedException(\DSI\NotEnoughData::class);
        $this->countryRepo->insert($country);
    }

    /** @test save */
    public function cannotSaveWithAnEmptyName()
    {
        $country = new Country();
        $country->setName('test');
        $this->countryRepo->insert($country);

        $country->setName('');
        $this->setExpectedException(\DSI\NotEnoughData::class);
        $this->countryRepo->save($country);
    }

    /** @test save */
    public function cannotSaveAsNew2CountriesWithTheSameName()
    {
        $country = new Country();
        $country->setName('test');
        $this->countryRepo->insert($country);

        $country = new Country();
        $country->setName('test');
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->countryRepo->insert($country);
    }

    /** @test save */
    public function cannotSave2CountriesWithTheSameName()
    {
        $country_1 = new Country();
        $country_1->setName('test');
        $this->countryRepo->insert($country_1);

        $country_2 = new Country();
        $country_2->setName('test2');
        $this->countryRepo->insert($country_2);

        $country_2->setName('test');
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->countryRepo->save($country_2);
    }

    /** @test getAll */
    public function getAllCountries()
    {
        $countries = new Country();
        $countries->setName('test');
        $this->countryRepo->insert($countries);

        $this->assertCount(1, $this->countryRepo->getAll());

        $countries = new Country();
        $countries->setName('test2');
        $this->countryRepo->insert($countries);

        $this->assertCount(2, $this->countryRepo->getAll());
    }

    /** @test getAll */
    public function getByIds()
    {
        $country1 = new Country();
        $country1->setName('test');
        $this->countryRepo->insert($country1);

        $country2 = new Country();
        $country2->setName('test2');
        $this->countryRepo->insert($country2);

        $country3 = new Country();
        $country3->setName('test3');
        $this->countryRepo->insert($country3);

        $this->assertCount(0, $this->countryRepo->getByIds([]));
        $this->assertCount(1, $this->countryRepo->getByIds([$country1->getId()]));
        $this->assertCount(2, $this->countryRepo->getByIds([$country1->getId(), $country2->getId()]));
    }

    /** @test saveAsNew getById */
    public function setAllCountriesDetails()
    {
        $country = new Country();
        $country->setName('test');
        $this->countryRepo->insert($country);

        $sameTag = $this->countryRepo->getById($country->getId());
        $this->assertEquals($country->getName(), $sameTag->getName());
    }
}