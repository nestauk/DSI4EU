<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository\UserRepository;
use \DSI\Repository\ProjectRepository;
use \DSI\Repository\CountryRegionRepository;
use \DSI\Repository\CountryRepository;
use \DSI\Entity\Project;
use \DSI\Entity\User;
use \DSI\Entity\CountryRegion;
use \DSI\Entity\Country;

class ProjectRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var ProjectRepository */
    private $projectRepository;

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
        $this->projectRepository = new ProjectRepository();
        $this->userRepo = new UserRepository();
        $this->countryRegionRepo = new CountryRegionRepository();
        $this->countryRepo = new CountryRepository();

        $this->user1 = new User();
        $this->user2 = new User();
        $this->userRepo->insert($this->user1);
        $this->userRepo->insert($this->user2);

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
        $this->projectRepository->clearAll();
        $this->userRepo->clearAll();
        $this->countryRegionRepo->clearAll();
        $this->countryRepo->clearAll();
    }

    /** @test saveAsNew */
    public function projectCanBeSaved()
    {
        $project = new Project();
        $project->setOwner($this->user1);
        $this->projectRepository->insert($project);

        $this->assertEquals(1, $project->getId());
    }

    /** @test save, getByID */
    public function projectCanBeUpdated()
    {
        $project = new Project();
        $project->setOwner($this->user1);
        $this->projectRepository->insert($project);

        $project->setOwner($this->user2);
        $this->projectRepository->save($project);

        $sameProject = $this->projectRepository->getById($project->getId());
        $this->assertEquals($this->user2->getId(), $sameProject->getOwner()->getId());
    }

    /** @test getByID */
    public function gettingAnNonExistentProjectById_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->projectRepository->getById(1);
    }

    /** @test save */
    public function NonexistentProjectCannotBeSaved()
    {
        $project = new Project();
        $project->setId(1);
        $project->setOwner($this->user1);

        $this->setExpectedException(\DSI\NotFound::class);
        $this->projectRepository->save($project);
    }

    /** @test getAll */
    public function getAllProjects()
    {
        $project = new Project();
        $project->setOwner($this->user1);
        $this->projectRepository->insert($project);

        $this->assertCount(1, $this->projectRepository->getAll());

        $project = new Project();
        $project->setOwner($this->user1);
        $this->projectRepository->insert($project);

        $this->assertCount(2, $this->projectRepository->getAll());
    }

    /** @test saveAsNew getById */
    public function setAllProjectsDetails()
    {
        $project = new Project();
        $project->setOwner($this->user1);
        $project->setName('Name');
        $project->setDescription('Desc');
        $project->setUrl('http://example.org');
        $project->setStatus('closed');
        $project->setStartDate('2016-05-21');
        $project->setEndDate('2016-05-22');
        $project->setCountryRegion($this->countryRegion);
        $project->setOrganisationsCount(10);
        $this->projectRepository->insert($project);

        $project = $this->projectRepository->getById($project->getId());
        $this->assertEquals($this->user1->getId(), $project->getOwner()->getId());
        $this->assertEquals('Name', $project->getName());
        $this->assertEquals('Desc', $project->getDescription());
        $this->assertEquals('http://example.org', $project->getUrl());
        $this->assertEquals('closed', $project->getStatus());
        $this->assertEquals('2016-05-21', $project->getStartDate());
        $this->assertEquals('2016-05-22', $project->getEndDate());
        $this->assertEquals($this->countryRegion->getId(), $project->getCountryRegion()->getId());
        $this->assertEquals($this->countryRegion->getCountry()->getId(), $project->getCountry()->getId());
        $this->assertEquals(10, $project->getOrganisationsCount());
    }

    /** @test */
    public function setNullStartDateAndEndDate()
    {
        $project = new Project();
        $project->setOwner($this->user1);
        $project->setStartDate(NULL);
        $project->setEndDate(NULL);
        $this->projectRepository->insert($project);

        $sameProject = $this->projectRepository->getById($project->getId());
        $this->assertNull($sameProject->getStartDate());
        $this->assertNull($sameProject->getEndDate());
    }
}