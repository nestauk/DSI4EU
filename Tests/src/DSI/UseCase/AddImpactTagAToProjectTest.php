<?php

require_once __DIR__ . '/../../../config.php';

class AddImpactTagAToProjectTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\AddImpactTagAToProject */
    private $addImpactTagToProject;

    /** @var \DSI\Repository\ImpactTagRepository */
    private $impactTagRepo;

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
        $this->addImpactTagToProject = new \DSI\UseCase\AddImpactTagAToProject();
        $this->impactTagRepo = new \DSI\Repository\ImpactTagRepository();
        $this->projectRepo = new \DSI\Repository\ProjectRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();

        $this->user = new \DSI\Entity\User();
        $this->userRepo->saveAsNew($this->user);

        $this->project = new \DSI\Entity\Project();
        $this->project->setOwner($this->user);
        $this->projectRepo->saveAsNew($this->project);
    }

    public function tearDown()
    {
        $this->impactTagRepo->clearAll();
        $this->projectRepo->clearAll();
        $this->userRepo->clearAll();
        (new \DSI\Repository\ProjectImpactTagARepository())->clearAll();
    }

    /** @test */
    public function successfulAdditionOfTagToProject()
    {
        $this->addImpactTagToProject->data()->tag = 'test';
        $this->addImpactTagToProject->data()->projectID = $this->project->getId();
        $this->addImpactTagToProject->exec();

        $this->assertTrue(
            (new \DSI\Repository\ProjectImpactTagARepository())->projectHasTagName(
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