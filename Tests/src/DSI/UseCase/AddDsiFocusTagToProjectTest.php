<?php

require_once __DIR__ . '/../../../config.php';

class AddDsiFocusTagToProjectTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\AddDsiFocusTagToProject */
    private $addDsiFocusTagToProject;

    /** @var \DSI\Repository\DsiFocusTagRepository */
    private $dsiFocusTagRepository;

    /** @var \DSI\Repository\ProjectRepository */
    private $projectRepo;

    /** @var \DSI\Entity\Project */
    private $project;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user;

    public function setUp()
    {
        $this->addDsiFocusTagToProject = new \DSI\UseCase\AddDsiFocusTagToProject();
        $this->dsiFocusTagRepository = new \DSI\Repository\DsiFocusTagRepository();
        $this->projectRepo = new \DSI\Repository\ProjectRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();

        $this->user = new \DSI\Entity\User();
        $this->userRepo->insert($this->user);

        $this->project = new \DSI\Entity\Project();
        $this->project->setOwner($this->user);
        $this->projectRepo->insert($this->project);
    }

    public function tearDown()
    {
        $this->dsiFocusTagRepository->clearAll();
        $this->projectRepo->clearAll();
        $this->userRepo->clearAll();
        (new \DSI\Repository\ProjectDsiFocusTagRepository())->clearAll();
    }

    /** @test */
    public function successfulAdditionOfTagToProject()
    {
        $this->addDsiFocusTagToProject->data()->tag = 'test';
        $this->addDsiFocusTagToProject->data()->projectID = $this->project->getId();
        $this->addDsiFocusTagToProject->exec();

        $this->assertTrue(
            (new \DSI\Repository\ProjectDsiFocusTagRepository())->projectHasTagName(
                $this->addDsiFocusTagToProject->data()->projectID,
                $this->addDsiFocusTagToProject->data()->tag
            )
        );
    }

    /** @test */
    public function cannotAddSameTagTwice()
    {
        $e = null;
        $this->addDsiFocusTagToProject->data()->tag = 'test';
        $this->addDsiFocusTagToProject->data()->projectID = $this->project->getId();
        $this->addDsiFocusTagToProject->exec();

        try {
            $this->addDsiFocusTagToProject->data()->tag = 'test';
            $this->addDsiFocusTagToProject->data()->projectID = $this->project->getId();
            $this->addDsiFocusTagToProject->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('tag'));
    }
}