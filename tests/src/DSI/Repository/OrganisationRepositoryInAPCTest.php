<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository\UserRepo;
use \DSI\Repository\OrganisationRepoInAPC;
use \DSI\Repository\CountryRegionRepo;
use \DSI\Repository\CountryRepo;
use \DSI\Entity\Organisation;
use \DSI\Entity\User;
use \DSI\Entity\CountryRegion;
use \DSI\Entity\Country;

class OrganisationRepositoryInAPCTest extends PHPUnit_Framework_TestCase
{
    /** @var OrganisationRepoInAPC */
    private $organisationRepositoryInAPC;

    /** @var \DSI\Repository\OrganisationTypeRepo */
    private $organisationTypeRepo;

    /** @var \DSI\Repository\OrganisationSizeRepo */
    private $organisationSizeRepo;

    /** @var UserRepo */
    private $userRepo;

    /** @var CountryRegionRepo */
    private $countryRegionRepo;

    /** @var CountryRepo */
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
        $this->organisationRepositoryInAPC = new OrganisationRepoInAPC();
        $this->organisationTypeRepo = new \DSI\Repository\OrganisationTypeRepo();
        $this->organisationSizeRepo = new \DSI\Repository\OrganisationSizeRepo();
        $this->userRepo = new UserRepo();
        $this->countryRegionRepo = new CountryRegionRepo();
        $this->countryRepo = new CountryRepo();

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
        $this->organisationRepositoryInAPC->clearAll();
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
        $organisation->setType($this->organisationType);
        $organisation->setSize($this->organisationSize);
        $organisation->setStartDate($startDate = '2010-10-12');
        $organisation->setLogo($logo = 'DSC111.JPG');
        $organisation->setHeaderImage($headerImage = 'DSC222.JPG');
        $organisation->setProjectsCount($projectsCount = 10);
        $organisation->setPartnersCount($partnersCount = 10);
        $this->organisationRepositoryInAPC->insert($organisation);

        $this->assertEquals(1, $organisation->getId());
        $organisation = $this->organisationRepositoryInAPC->getById($organisation->getId());
        $this->assertEquals($this->user1->getId(), $organisation->getOwnerID());
        $this->assertEquals($name, $organisation->getName());
        $this->assertEquals($url, $organisation->getUrl());
        $this->assertEquals($shortDescription, $organisation->getShortDescription());
        $this->assertEquals($description, $organisation->getDescription());
        $this->assertEquals($this->countryRegion->getId(), $organisation->getRegion()->getId());
        $this->assertEquals($this->countryRegion->getCountry()->getId(), $organisation->getCountry()->getId());
        $this->assertEquals($address, $organisation->getAddress());
        $this->assertEquals($this->organisationType->getId(), $organisation->getTypeId());
        $this->assertEquals($this->organisationSize->getId(), $organisation->getSizeId());
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
        $this->organisationRepositoryInAPC->insert($organisation);

        $organisation->setOwner($this->user1);
        $organisation->setName($name = 'Name');
        $organisation->setUrl($url = 'http://example.org');
        $organisation->setShortDescription($shortDescription = 'Short Desc');
        $organisation->setDescription($description = 'Desc');
        $organisation->setCountryRegion($this->countryRegion);
        $organisation->setAddress($address = '58 New Street');
        $organisation->setType($this->organisationType);
        $organisation->setSize($this->organisationSize);
        $organisation->setStartDate($startDate = '2010-10-12');
        $organisation->setLogo($logo = 'DSC111.JPG');
        $organisation->setHeaderImage($headerImage = 'DSC222.JPG');
        $organisation->setProjectsCount($projectsCount = 10);
        $organisation->setPartnersCount($partnersCount = 10);
        $this->organisationRepositoryInAPC->save($organisation);

        $organisation = $this->organisationRepositoryInAPC->getById($organisation->getId());
        $this->assertEquals($this->user1->getId(), $organisation->getOwnerID());
        $this->assertEquals($name, $organisation->getName());
        $this->assertEquals($url, $organisation->getUrl());
        $this->assertEquals($shortDescription, $organisation->getShortDescription());
        $this->assertEquals($description, $organisation->getDescription());
        $this->assertEquals($this->countryRegion->getId(), $organisation->getRegion()->getId());
        $this->assertEquals($this->countryRegion->getCountry()->getId(), $organisation->getCountry()->getId());
        $this->assertEquals($address, $organisation->getAddress());
        $this->assertEquals($this->organisationType->getId(), $organisation->getTypeId());
        $this->assertEquals($this->organisationSize->getId(), $organisation->getSizeId());
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
        $this->organisationRepositoryInAPC->getById(1);
    }

    /** @test save */
    public function NonexistentOrganisationCannotBeSaved()
    {
        $organisation = new Organisation();
        $organisation->setId(1);
        $organisation->setOwner($this->user1);

        $this->setExpectedException(\DSI\NotFound::class);
        $this->organisationRepositoryInAPC->save($organisation);
    }

    /** @test */
    public function settingKey_canGetKey()
    {
        OrganisationRepoInAPC::setApcKey($key = 'new-key');
        $this->assertEquals($key, OrganisationRepoInAPC::getApcKey());
    }

    /** @test getAll */
    public function getAllOrganisations()
    {
        $organisation = new Organisation();
        $organisation->setOwner($this->user1);
        $this->organisationRepositoryInAPC->insert($organisation);

        $this->assertCount(1, $this->organisationRepositoryInAPC->getAll());
        $this->assertEquals(1, $this->organisationRepositoryInAPC->countAll());

        $organisation = new Organisation();
        $organisation->setOwner($this->user1);
        $this->organisationRepositoryInAPC->insert($organisation);

        $this->assertCount(2, $this->organisationRepositoryInAPC->getAll());
        $this->assertEquals(2, $this->organisationRepositoryInAPC->countAll());
    }

    /** @test */
    public function searchByTitle()
    {
        $this->createOrganisation('Organisation 1');
        $this->createOrganisation('Organisation 2');
        $this->createOrganisation('Organisation X');
        $this->createOrganisation('Category 1');

        $this->assertCount(0, $this->organisationRepositoryInAPC->searchByTitle('3'));
        $this->assertCount(1, $this->organisationRepositoryInAPC->searchByTitle('2'));
        $this->assertCount(2, $this->organisationRepositoryInAPC->searchByTitle(' 1'));
        $this->assertCount(3, $this->organisationRepositoryInAPC->searchByTitle('Organisation'));
        $this->assertCount(3, $this->organisationRepositoryInAPC->searchByTitle(' ', 3));
        $this->assertCount(4, $this->organisationRepositoryInAPC->searchByTitle(' '));
    }

    /**
     * @param $name
     */
    private function createOrganisation($name)
    {
        $organisation = new Organisation();
        $organisation->setOwner($this->user1);
        $organisation->setName($name);
        $this->organisationRepositoryInAPC->insert($organisation);
    }
}