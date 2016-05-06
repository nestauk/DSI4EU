<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository\UserRepository;
use \DSI\Repository\ProjectRepository;
use \DSI\Entity\Project;
use \DSI\Entity\User;

class ProjectRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var ProjectRepository */
    private $projectRepository;

    /** @var UserRepository */
    private $userRepo;

    /** @var User */
    private $user1, $user2;

    public function setUp()
    {
        $this->projectRepository = new ProjectRepository();
        $this->userRepo = new UserRepository();

        $this->user1 = new User();
        $this->userRepo->saveAsNew($this->user1);
        $this->user2 = new User();
        $this->userRepo->saveAsNew($this->user2);
    }

    public function tearDown()
    {
        $this->projectRepository->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test saveAsNew */
    public function projectCanBeSaved()
    {
        $project = new Project();
        $project->setOwner($this->user1);
        $this->projectRepository->saveAsNew($project);

        $this->assertEquals(1, $project->getId());
    }

    /** @test save, getByID */
    public function projectCanBeUpdated()
    {
        $project = new Project();
        $project->setOwner($this->user1);
        $this->projectRepository->saveAsNew($project);

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
        $this->projectRepository->saveAsNew($project);

        $this->assertCount(1, $this->projectRepository->getAll());

        $project = new Project();
        $project->setOwner($this->user1);
        $this->projectRepository->saveAsNew($project);

        $this->assertCount(2, $this->projectRepository->getAll());
    }

    /** @test saveAsNew getById */
    public function setAllProjectsDetails()
    {
        $project = new Project();
        $project->setOwner($this->user1);
        $project->setName('Name');
        $project->setDescription('Desc');
        $this->projectRepository->saveAsNew($project);

        $sameProject = $this->projectRepository->getById( $project->getId() );
        $this->assertEquals($project->getOwner()->getId(), $sameProject->getOwner()->getId());
        $this->assertEquals($project->getName(), $sameProject->getName());
        $this->assertEquals($project->getDescription(), $sameProject->getDescription());
    }
}