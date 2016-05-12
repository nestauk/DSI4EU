<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository\UserRepository;
use \DSI\Repository\OrganisationRepository;
use \DSI\Repository\CountryRegionRepository;
use \DSI\Repository\CountryRepository;
use \DSI\Entity\Organisation;
use \DSI\Entity\User;
use \DSI\Entity\CountryRegion;
use \DSI\Entity\Country;

class OrganisationRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var OrganisationRepository */
    private $organisationRepo;

    /** @var \DSI\Repository\OrganisationTypeRepository */
    private $organisationTypeRepo;

    /** @var \DSI\Repository\OrganisationSizeRepository */
    private $organisationSizeRepo;

    /** @var UserRepository */
    private $userRepo;

    /** @var CountryRegionRepository */
    private $countryRegionRepo;

    /** @var CountryRepository */
    private $countryRepo;

    /** @var Country */
    private $country;

    /** @var CountryRegion */
    private $countryRegion;

    /** @var User */
    private $user1, $user2;

    public function setUp()
    {
        $this->organisationRepo = new OrganisationRepository();
        $this->organisationTypeRepo = new \DSI\Repository\OrganisationTypeRepository();
        $this->organisationSizeRepo = new \DSI\Repository\OrganisationSizeRepository();
        $this->userRepo = new UserRepository();
        $this->countryRegionRepo = new CountryRegionRepository();
        $this->countryRepo = new CountryRepository();

        $this->user1 = new User();
        $this->user2 = new User();
        $this->userRepo->saveAsNew($this->user1);
        $this->userRepo->saveAsNew($this->user2);

        $this->country = new Country();
        $this->country->setName('test1');
        $this->countryRepo->saveAsNew($this->country);

        $this->countryRegion = new CountryRegion();
        $this->countryRegion->setName('test1');
        $this->countryRegion->setCountry($this->country);
        $this->countryRegionRepo->saveAsNew($this->countryRegion);
    }

    public function tearDown()
    {
        $this->organisationRepo->clearAll();
        $this->organisationTypeRepo->clearAll();
        $this->organisationSizeRepo->clearAll();
        $this->userRepo->clearAll();
        $this->countryRegionRepo->clearAll();
        $this->countryRepo->clearAll();
    }

    /** @test saveAsNew */
    public function organisationCanBeSaved()
    {
        $organisation = new Organisation();
        $organisation->setOwner($this->user1);
        $this->organisationRepo->saveAsNew($organisation);

        $this->assertEquals(1, $organisation->getId());
    }

    /** @test save, getByID */
    public function organisationCanBeUpdated()
    {
        $organisation = new Organisation();
        $organisation->setOwner($this->user1);
        $this->organisationRepo->saveAsNew($organisation);

        $organisation->setOwner($this->user2);
        $this->organisationRepo->save($organisation);

        $sameProject = $this->organisationRepo->getById($organisation->getId());
        $this->assertEquals($this->user2->getId(), $sameProject->getOwner()->getId());
    }

    /** @test getByID */
    public function gettingAnNonExistentOrganisationById_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->organisationRepo->getById(1);
    }

    /** @test save */
    public function NonexistentOrganisationCannotBeSaved()
    {
        $organisation = new Organisation();
        $organisation->setId(1);
        $organisation->setOwner($this->user1);

        $this->setExpectedException(\DSI\NotFound::class);
        $this->organisationRepo->save($organisation);
    }

    /** @test getAll */
    public function getAllOrganisations()
    {
        $organisation = new Organisation();
        $organisation->setOwner($this->user1);
        $this->organisationRepo->saveAsNew($organisation);

        $this->assertCount(1, $this->organisationRepo->getAll());

        $organisation = new Organisation();
        $organisation->setOwner($this->user1);
        $this->organisationRepo->saveAsNew($organisation);

        $this->assertCount(2, $this->organisationRepo->getAll());
    }

    /** @test saveAsNew getById */
    public function setAllOrganisationDetails()
    {
        $organisationType = new \DSI\Entity\OrganisationType();
        $this->organisationTypeRepo->saveAsNew($organisationType);

        $organisationSize = new \DSI\Entity\OrganisationSize();
        $this->organisationSizeRepo->saveAsNew($organisationSize);

        $organisation = new Organisation();
        $organisation->setOwner($this->user1);
        $organisation->setName('Name');
        $organisation->setDescription('Desc');
        $organisation->setCountryRegion($this->countryRegion);
        $organisation->setOrganisationType($organisationType);
        $organisation->setOrganisationSize($organisationSize);
        $this->organisationRepo->saveAsNew($organisation);

        $sameOrganisation = $this->organisationRepo->getById($organisation->getId());
        $this->assertEquals($organisation->getOwner()->getId(), $sameOrganisation->getOwner()->getId());
        $this->assertEquals($organisation->getName(), $sameOrganisation->getName());
        $this->assertEquals($organisation->getDescription(), $sameOrganisation->getDescription());
        $this->assertEquals($organisation->getCountryRegion()->getId(), $sameOrganisation->getCountryRegion()->getId());
        $this->assertEquals($organisation->getCountry()->getId(), $sameOrganisation->getCountry()->getId());
        $this->assertEquals($organisation->getOrganisationType()->getId(), $sameOrganisation->getOrganisationType()->getId());
        $this->assertEquals($organisation->getOrganisationSize()->getId(), $sameOrganisation->getOrganisationSize()->getId());
    }
}