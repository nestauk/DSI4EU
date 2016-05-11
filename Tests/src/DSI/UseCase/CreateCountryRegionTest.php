<?php

require_once __DIR__ . '/../../../config.php';

class CreateCountryRegionTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\CreateCountryRegion */
    private $createCountryRegionCmd;

    /** @var \DSI\Repository\CountryRegionRepository */
    private $countryRegionRepo;

    /** @var \DSI\Repository\CountryRepository */
    private $countryRepo;

    /** @var  \DSI\Entity\Country */
    private $country;

    public function setUp()
    {
        $this->createCountryRegionCmd = new \DSI\UseCase\CreateCountryRegion();
        $this->countryRegionRepo = new \DSI\Repository\CountryRegionRepository();
        $this->countryRepo = new \DSI\Repository\CountryRepository();

        $this->country = new \DSI\Entity\Country();
        $this->country->setName('test');
        $this->countryRepo->saveAsNew($this->country);
    }

    public function tearDown()
    {
        $this->countryRegionRepo->clearAll();
        $this->countryRepo->clearAll();
    }

    /** @test */
    public function successfulCreation()
    {
        $this->createCountryRegionCmd->data()->name = 'test';
        $this->createCountryRegionCmd->data()->countryID = $this->country->getId();
        $this->createCountryRegionCmd->exec();

        $this->assertCount(1, $this->countryRegionRepo->getAll());

        $this->createCountryRegionCmd->data()->name = 'test2';
        $this->createCountryRegionCmd->data()->countryID = $this->country->getId();
        $this->createCountryRegionCmd->exec();

        $this->assertCount(2, $this->countryRegionRepo->getAll());
    }

    /** @test */
    public function cannotAddSameRegionTwice()
    {
        $e = null;

        $this->createCountryRegionCmd->data()->name = 'test';
        $this->createCountryRegionCmd->data()->countryID = $this->country->getId();
        $this->createCountryRegionCmd->exec();

        $this->assertCount(1, $this->countryRegionRepo->getAll());

        $this->createCountryRegionCmd->data()->name = 'test';
        $this->createCountryRegionCmd->data()->countryID = $this->country->getId();
        try {
            $this->createCountryRegionCmd->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('region'));
    }
}