<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository\UserRepository;
use \DSI\Repository\ProjectRepositoryInAPC;
use \DSI\Repository\CountryRegionRepository;
use \DSI\Repository\CountryRepository;
use \DSI\Entity\Project;
use \DSI\Entity\User;
use \DSI\Entity\CountryRegion;
use \DSI\Entity\Country;

class ProjectRepositoryInAPCTest extends PHPUnit_Framework_TestCase
{
    /** @var ProjectRepositoryInAPC */
    private $projectRepositoryInAPC;

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
        $this->projectRepositoryInAPC = new ProjectRepositoryInAPC();
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
    }

    public function tearDown()
    {
        $this->projectRepositoryInAPC->clearAll();
        $this->userRepo->clearAll();
        $this->countryRegionRepo->clearAll();
        $this->countryRepo->clearAll();
    }

    /** @test saveAsNew */
    public function projectCanBeCreated()
    {
        $project = new Project();
        $project->setOwner($this->user1);
        $project->setName($name = 'Name');
        $project->setShortDescription($shortDesc = 'Short Desc');
        $project->setDescription($desc = 'Desc');
        $project->setUrl($url = 'http://example.org');
        $project->setStatus($status = 'closed');
        $project->setStartDate($startDate = '2016-05-21');
        $project->setEndDate($endDate = '2016-05-22');
        $project->setCountryRegion($this->countryRegion);
        $project->setOrganisationsCount($organisations = 10);
        $project->setLogo($logo = 'DSC111.JPG');
        $project->setHeaderImage($headerImage = 'DSC222.JPG');
        $project->setSocialImpact($socialImpact = 'Social Impact');
        $project->setIsPublished($isPublished = true);
        $this->projectRepositoryInAPC->insert($project);

        $this->assertEquals(1, $project->getId());
        $project = $this->projectRepositoryInAPC->getById($project->getId());
        $this->assertEquals($this->user1->getId(), $project->getOwner()->getId());
        $this->assertEquals($name, $project->getName());
        $this->assertEquals($shortDesc, $project->getShortDescription());
        $this->assertEquals($desc, $project->getDescription());
        $this->assertEquals($url, $project->getUrl());
        $this->assertEquals($status, $project->getStatus());
        $this->assertEquals($startDate, $project->getStartDate());
        $this->assertEquals($endDate, $project->getEndDate());
        $this->assertEquals($this->countryRegion->getId(), $project->getCountryRegion()->getId());
        $this->assertEquals($this->countryRegion->getCountry()->getId(), $project->getCountry()->getId());
        $this->assertEquals($organisations, $project->getOrganisationsCount());
        $this->assertEquals($logo, $project->getLogo());
        $this->assertEquals($headerImage, $project->getHeaderImage());
        $this->assertEquals($socialImpact, $project->getSocialImpact());
        $this->assertEquals($isPublished, $project->isPublished());
    }

    /** @test getByID */
    public function gettingAnNonExistentProjectById_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->projectRepositoryInAPC->getById(1);
    }

    /** @test save */
    public function NonexistentProjectCannotBeSaved()
    {
        $project = new Project();
        $project->setId(1);
        $project->setOwner($this->user1);

        $this->setExpectedException(\DSI\NotFound::class);
        $this->projectRepositoryInAPC->save($project);
    }

    /** @test getAll */
    public function getAllProjects()
    {
        $project = new Project();
        $project->setOwner($this->user1);
        $this->projectRepositoryInAPC->insert($project);

        $this->assertCount(1, $this->projectRepositoryInAPC->getAll());
        $this->assertEquals(1, $this->projectRepositoryInAPC->countAll());

        $project = new Project();
        $project->setOwner($this->user1);
        $this->projectRepositoryInAPC->insert($project);

        $this->assertCount(2, $this->projectRepositoryInAPC->getAll());
        $this->assertEquals(2, $this->projectRepositoryInAPC->countAll());
    }

    /** @test */
    public function projectCanBeUpdated()
    {
        $project = new Project();
        $project->setOwner($this->user1);
        $this->projectRepositoryInAPC->insert($project);

        $project->setOwner($this->user1);
        $project->setName($name = 'Name');
        $project->setShortDescription($shortDesc = 'Short Desc');
        $project->setDescription($desc = 'Desc');
        $project->setUrl($url = 'http://example.org');
        $project->setStatus($status = 'closed');
        $project->setStartDate($startDate = '2016-05-21');
        $project->setEndDate($endDate = '2016-05-22');
        $project->setCountryRegion($this->countryRegion);
        $project->setOrganisationsCount($organisations = 10);
        $project->setLogo($logo = 'DSC111.JPG');
        $project->setHeaderImage($headerImage = 'DSC222.JPG');
        $project->setSocialImpact($socialImpact = 'Social Impact');
        $project->setIsPublished($isPublished = true);
        $this->projectRepositoryInAPC->save($project);

        $project = $this->projectRepositoryInAPC->getById($project->getId());
        $this->assertEquals($this->user1->getId(), $project->getOwner()->getId());
        $this->assertEquals($name, $project->getName());
        $this->assertEquals($shortDesc, $project->getShortDescription());
        $this->assertEquals($desc, $project->getDescription());
        $this->assertEquals($url, $project->getUrl());
        $this->assertEquals($status, $project->getStatus());
        $this->assertEquals($startDate, $project->getStartDate());
        $this->assertEquals($endDate, $project->getEndDate());
        $this->assertEquals($this->countryRegion->getId(), $project->getCountryRegion()->getId());
        $this->assertEquals($this->countryRegion->getCountry()->getId(), $project->getCountry()->getId());
        $this->assertEquals($organisations, $project->getOrganisationsCount());
        $this->assertEquals($logo, $project->getLogo());
        $this->assertEquals($headerImage, $project->getHeaderImage());
        $this->assertEquals($socialImpact, $project->getSocialImpact());
        $this->assertEquals($isPublished, $project->isPublished());
    }

    /** @test */
    public function setNullStartDateAndEndDate()
    {
        $project = new Project();
        $project->setOwner($this->user1);
        $project->setStartDate(NULL);
        $project->setEndDate(NULL);
        $this->projectRepositoryInAPC->insert($project);

        $sameProject = $this->projectRepositoryInAPC->getById($project->getId());
        $this->assertNull($sameProject->getStartDate());
        $this->assertNull($sameProject->getEndDate());
    }

    /** @test */
    public function searchByTitle()
    {
        $this->createProject('Project 1');
        $this->createProject('Project 2');
        $this->createProject('Project X');
        $this->createProject('Category 1');

        $this->assertCount(0, $this->projectRepositoryInAPC->searchByTitle('3'));
        $this->assertCount(1, $this->projectRepositoryInAPC->searchByTitle('2'));
        $this->assertCount(2, $this->projectRepositoryInAPC->searchByTitle(' 1'));
        $this->assertCount(3, $this->projectRepositoryInAPC->searchByTitle('Project'));
        $this->assertCount(3, $this->projectRepositoryInAPC->searchByTitle(' ', 3));
        $this->assertCount(4, $this->projectRepositoryInAPC->searchByTitle(' '));
    }

    /**
     * @param $name
     */
    private function createProject($name)
    {
        $project = new Project();
        $project->setOwner($this->user1);
        $project->setName($name);
        $this->projectRepositoryInAPC->insert($project);
    }
}