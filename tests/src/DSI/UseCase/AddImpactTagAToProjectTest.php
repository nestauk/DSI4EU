<?php

require_once __DIR__ . '/../../../config.php';

class AddImpactTagAToProjectTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\AddImpactHelpTagToProject */
    private $addImpactTagToProject;

    /** @var \DSI\Repository\ImpactTagRepo */
    private $impactTagRepo;

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
        $this->addImpactTagToProject = new \DSI\UseCase\AddImpactHelpTagToProject();
        $this->impactTagRepo = new \DSI\Repository\ImpactTagRepo();
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
        $this->impactTagRepo->clearAll();
        $this->projectRepo->clearAll();
        $this->userRepo->clearAll();
        (new \DSI\Repository\ProjectImpactHelpTagRepo())->clearAll();
    }

    /** @test */
    public function successfulAdditionOfTagToProject()
    {
        $this->addImpactTagToProject->data()->tag = 'test';
        $this->addImpactTagToProject->data()->projectID = $this->project->getId();
        $this->addImpactTagToProject->exec();

        $this->assertTrue(
            (new \DSI\Repository\ProjectImpactHelpTagRepo())->projectHasTagName(
                $this->addImpactTagToProject->data()->projectID,
                $this->addImpactTagToProject->data()->tag
            )
        );
    }

    /** @test */
    public function cannotAddSameTagTwice()
    {
        $e = null;
        $this->addImpactTagToProject->data()->tag = 'test';
        $this->addImpactTagToProject->data()->projectID = $this->project->getId();
        $this->addImpactTagToProject->exec();

        try {
            $this->addImpactTagToProject->data()->tag = 'test';
            $this->addImpactTagToProject->data()->projectID = $this->project->getId();
            $this->addImpactTagToProject->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('tag'));
    }
}