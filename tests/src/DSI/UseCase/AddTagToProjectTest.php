<?php

require_once __DIR__ . '/../../../config.php';

class AddTagToProjectTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\AddTagToProject */
    private $addTagToProject;

    /** @var \DSI\Repository\TagForProjectsRepo */
    private $tagRepo;

    /** @var \DSI\Repository\ProjectRepo */
    private $projectRepo;

    /** @var \DSI\Entity\Project */
    private $project;

    /** @var \DSI\Repository\UserRepo */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user;

    public function setUp()
    {
        $this->addTagToProject = new \DSI\UseCase\AddTagToProject();
        $this->tagRepo = new \DSI\Repository\TagForProjectsRepo();
        $this->projectRepo = new \DSI\Repository\ProjectRepo();
        $this->userRepo = new \DSI\Repository\UserRepo();

        $this->user = new \DSI\Entity\User();
        $this->userRepo->insert($this->user);

        $this->project = new \DSI\Entity\Project();
        $this->project->setOwner($this->user);
        $this->projectRepo->insert($this->project);
    }

    public function tearDown()
    {
        $this->tagRepo->clearAll();
        $this->projectRepo->clearAll();
        $this->userRepo->clearAll();
        (new \DSI\Repository\ProjectTagRepo())->clearAll();
    }

    /** @test */
    public function successfulAdditionOfTagToProject()
    {
        $this->addTagToProject->data()->tag = 'test';
        $this->addTagToProject->data()->projectID = $this->project->getId();
        $this->addTagToProject->exec();

        $this->assertTrue(
            (new \DSI\Repository\ProjectTagRepo())->projectHasTagName(
                $this->addTagToProject->data()->projectID,
                $this->addTagToProject->data()->tag
            )
        );
    }

    /** @test */
    public function cannotAddSameTagTwice()
    {
        $e = null;
        $this->addTagToProject->data()->tag = 'test';
        $this->addTagToProject->data()->projectID = $this->project->getId();
        $this->addTagToProject->exec();

        try {
            $this->addTagToProject->data()->tag = 'test';
            $this->addTagToProject->data()->projectID = $this->project->getId();
            $this->addTagToProject->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('tag'));
    }
}