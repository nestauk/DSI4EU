<?php

require_once __DIR__ . '/../../../config.php';

use DSI\Service\ErrorHandler;

class UpdateOrganisationCountryRegionTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Entity\CountryRegion */
    private $countryRegion;
    /** @var \DSI\Entity\Country */
    private $country;
    /** @var \DSI\Repository\CountryRegionRepository */
    private $countryRegionRepository;
    /** @var \DSI\Repository\CountryRepository */
    private $countryRepository;
    /** @var \DSI\UseCase\UpdateOrganisationCountryRegion */
    private $updateOrganisationCountryRegionCmd;

    /** @var \DSI\Repository\OrganisationRepository */
    private $organisationRepo;

    /** @var \DSI\Entity\Organisation */
    private $organisation;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user;

    public function setUp()
    {
        $this->updateOrganisationCountryRegionCmd = new \DSI\UseCase\UpdateOrganisationCountryRegion();
        $this->userRepo = new \DSI\Repository\UserRepository();
        $this->countryRepository = new \DSI\Repository\CountryRepository();
        $this->countryRegionRepository = new \DSI\Repository\CountryRegionRepository();

        $this->country = new \DSI\Entity\Country();
        $this->country->setName('test');
        $this->countryRepository->saveAsNew($this->country);

        $this->countryRegion = new \DSI\Entity\CountryRegion();
        $this->countryRegion->setName('test');
        $this->countryRegion->setCountry($this->country);
        $this->countryRegionRepository->saveAsNew($this->countryRegion);

        $this->user = new \DSI\Entity\User();
        $this->userRepo->insert($this->user);

        $this->organisationRepo = new \DSI\Repository\OrganisationRepository();
        $this->organisation = new \DSI\Entity\Organisation();
        $this->organisation->setOwner($this->user);
        $this->organisationRepo->insert($this->organisation);
    }

    public function tearDown()
    {
        $this->organisationRepo->clearAll();
        $this->userRepo->clearAll();
        $this->countryRegionRepository->clearAll();
        $this->countryRepository->clearAll();
    }

    /** @test */
    public function successfulUpdateWithExistingRegion()
    {
        try {
            $this->updateOrganisationCountryRegionCmd->data()->organisationID = $this->organisation->getId();
            $this->updateOrganisationCountryRegionCmd->data()->countryID = $this->country->getId();
            $this->updateOrganisationCountryRegionCmd->data()->region = $this->countryRegion->getName();
            $this->updateOrganisationCountryRegionCmd->exec();
        } catch (ErrorHandler $e) {
            $this->assertNull($e);
        }

        $organisation = $this->organisationRepo->getById($this->organisation->getId());
        $this->assertEquals($this->country->getId(), $organisation->getCountryID());
        $this->assertEquals($this->countryRegion->getId(), $organisation->getCountryRegionID());
    }

    /** @test */
    public function successfulUpdateWithNonexistentRegion_CreatesNewRegion()
    {
        $this->assertCount(1, $this->countryRegionRepository->getAll());

        try {
            $this->updateOrganisationCountryRegionCmd->data()->organisationID = $this->organisation->getId();
            $this->updateOrganisationCountryRegionCmd->data()->countryID = $this->country->getId();
            $this->updateOrganisationCountryRegionCmd->data()->region = 'new region name';
            $this->updateOrganisationCountryRegionCmd->exec();
        } catch (ErrorHandler $e) {
            $this->assertNull($e);
        }

        $organisation = $this->organisationRepo->getById($this->organisation->getId());
        $this->assertEquals($this->country->getId(), $organisation->getCountryID());

        $this->assertCount(2, $this->countryRegionRepository->getAll());
    }

    /** @test */
    public function cannotExecWithoutAOrganisationID()
    {
        $e = null;
        try {
            $this->updateOrganisationCountryRegionCmd->data()->countryID = $this->country->getId();
            $this->updateOrganisationCountryRegionCmd->data()->region = $this->countryRegion->getId();
            $this->updateOrganisationCountryRegionCmd->exec();
        } catch (ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('organisation'));
    }

    /** @test */
    public function cannotExecWithoutARegionName()
    {
        $e = null;
        try {
            $this->updateOrganisationCountryRegionCmd->data()->organisationID = $this->organisation->getId();
            $this->updateOrganisationCountryRegionCmd->data()->countryID = $this->country->getId();
            $this->updateOrganisationCountryRegionCmd->exec();
        } catch (ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('region'));
    }

    /** @test */
    public function cannotExecWithoutACountry()
    {
        $e = null;
        try {
            $this->updateOrganisationCountryRegionCmd->data()->organisationID = $this->organisation->getId();
            $this->updateOrganisationCountryRegionCmd->data()->region = $this->countryRegion->getId();
            $this->updateOrganisationCountryRegionCmd->exec();
        } catch (ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('country'));
    }
}