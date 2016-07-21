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

    /** @var \DSI\Entity\OrganisationType */
    private $organisationType;

    /** @var \DSI\Entity\OrganisationSize */
    private $organisationSize;

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
        $this->userRepo->insert($this->user1);
        $this->userRepo->insert($this->user2);

        $this->country = new Country();
        $this->country->setName('test1');
        $this->countryRepo->insert($this->country);

        $this->countryRegion = new CountryRegion();
        $this->countryRegion->setName('test1');
        $this->countryRegion->setCountry($this->country);
        $this->countryRegionRepo->insert($this->countryRegion);

        $this->organisationType = new \DSI\Entity\OrganisationType();
        $this->organisationTypeRepo->insert($this->organisationType);

        $this->organisationSize = new \DSI\Entity\OrganisationSize();
        $this->organisationSizeRepo->insert($this->organisationSize);
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
        $organisation->setName($name = 'Name');
        $organisation->setUrl($url = 'http://example.org');
        $organisation->setShortDescription($shortDescription = 'Short Desc');
        $organisation->setDescription($description = 'Desc');
        $organisation->setCountryRegion($this->countryRegion);
        $organisation->setAddress($address = '58 New Street');
        $organisation->setOrganisationType($this->organisationType);
        $organisation->setOrganisationSize($this->organisationSize);
        $organisation->setStartDate($startDate = '2010-10-12');
        $organisation->setLogo($logo = 'DSC111.JPG');
        $organisation->setHeaderImage($headerImage = 'DSC222.JPG');
        $organisation->setProjectsCount($projectsCount = 10);
        $organisation->setPartnersCount($partnersCount = 10);
        $this->organisationRepo->insert($organisation);

        $this->assertEquals(1, $organisation->getId());
        $organisation = $this->organisationRepo->getById($organisation->getId());
        $this->assertEquals($this->user1->getId(), $organisation->getOwner()->getId());
        $this->assertEquals($name, $organisation->getName());
        $this->assertEquals($url, $organisation->getUrl());
        $this->assertEquals($shortDescription, $organisation->getShortDescription());
        $this->assertEquals($description, $organisation->getDescription());
        $this->assertEquals($this->countryRegion->getId(), $organisation->getCountryRegion()->getId());
        $this->assertEquals($this->countryRegion->getCountry()->getId(), $organisation->getCountry()->getId());
        $this->assertEquals($address, $organisation->getAddress());
        $this->assertEquals($this->organisationType->getId(), $organisation->getOrganisationTypeId());
        $this->assertEquals($this->organisationSize->getId(), $organisation->getOrganisationSizeId());
        $this->assertEquals($startDate, $organisation->getStartDate());
        $this->assertEquals($logo, $organisation->getLogo());
        $this->assertEquals($headerImage, $organisation->getHeaderImage());
        $this->assertEquals($projectsCount, $organisation->getProjectsCount());
        $this->assertEquals($partnersCount, $organisation->getPartnersCount());
    }

    /** @test */
    public function organisationCanBeUpdated()
    {
        $organisation = new Organisation();
        $organisation->setOwner($this->user1);
        $this->organisationRepo->insert($organisation);

        $organisation->setOwner($this->user1);
        $organisation->setName($name = 'Name');
        $organisation->setUrl($url = 'http://example.org');
        $organisation->setShortDescription($shortDescription = 'Short Desc');
        $organisation->setDescription($description = 'Desc');
        $organisation->setCountryRegion($this->countryRegion);
        $organisation->setAddress($address = '58 New Street');
        $organisation->setOrganisationType($this->organisationType);
        $organisation->setOrganisationSize($this->organisationSize);
        $organisation->setStartDate($startDate = '2010-10-12');
        $organisation->setLogo($logo = 'DSC111.JPG');
        $organisation->setHeaderImage($headerImage = 'DSC222.JPG');
        $organisation->setProjectsCount($projectsCount = 10);
        $organisation->setPartnersCount($partnersCount = 10);
        $this->organisationRepo->save($organisation);

        $organisation = $this->organisationRepo->getById($organisation->getId());
        $this->assertEquals($this->user1->getId(), $organisation->getOwner()->getId());
        $this->assertEquals($name, $organisation->getName());
        $this->assertEquals($url, $organisation->getUrl());
        $this->assertEquals($shortDescription, $organisation->getShortDescription());
        $this->assertEquals($description, $organisation->getDescription());
        $this->assertEquals($this->countryRegion->getId(), $organisation->getCountryRegion()->getId());
        $this->assertEquals($this->countryRegion->getCountry()->getId(), $organisation->getCountry()->getId());
        $this->assertEquals($address, $organisation->getAddress());
        $this->assertEquals($this->organisationType->getId(), $organisation->getOrganisationTypeId());
        $this->assertEquals($this->organisationSize->getId(), $organisation->getOrganisationSizeId());
        $this->assertEquals($startDate, $organisation->getStartDate());
        $this->assertEquals($logo, $organisation->getLogo());
        $this->assertEquals($headerImage, $organisation->getHeaderImage());
        $this->assertEquals($projectsCount, $organisation->getProjectsCount());
        $this->assertEquals($partnersCount, $organisation->getPartnersCount());
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
        $this->organisationRepo->insert($organisation);

        $this->assertCount(1, $this->organisationRepo->getAll());
        $this->assertEquals(1, $this->organisationRepo->countOrganisations());

        $organisation = new Organisation();
        $organisation->setOwner($this->user1);
        $this->organisationRepo->insert($organisation);

        $this->assertCount(2, $this->organisationRepo->getAll());
        $this->assertEquals(2, $this->organisationRepo->countOrganisations());
    }

    /** @test */
    public function searchByTitle()
    {
        $this->createOrganisation('Organisation 1');
        $this->createOrganisation('Organisation 2');
        $this->createOrganisation('Organisation X');
        $this->createOrganisation('Category 1');

        $this->assertCount(0, $this->organisationRepo->searchByTitle('3'));
        $this->assertCount(1, $this->organisationRepo->searchByTitle('2'));
        $this->assertCount(2, $this->organisationRepo->searchByTitle(' 1'));
        $this->assertCount(3, $this->organisationRepo->searchByTitle('Organisation'));
        $this->assertCount(3, $this->organisationRepo->searchByTitle(' ', 3));
        $this->assertCount(4, $this->organisationRepo->searchByTitle(' '));
    }

    /**
     * @param $name
     */
    private function createOrganisation($name)
    {
        $organisation = new Organisation();
        $organisation->setOwner($this->user1);
        $organisation->setName($name);
        $this->organisationRepo->insert($organisation);
    }
}