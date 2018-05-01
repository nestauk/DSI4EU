<?php

require_once __DIR__ . '/../../../config.php';

class AddMemberRequestToProjectTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\AddMemberRequestToProject */
    private $addMemberRequestToProject;

    /** @var \DSI\Repository\ProjectRepo */
    private $projectRepo;

    /** @var \DSI\Entity\Project */
    private $project;

    /** @var \DSI\Repository\UserRepo */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user_1, $user_2;

    public function setUp()
    {
        $this->addMemberRequestToProject = new \DSI\UseCase\AddMemberRequestToProject();
        $this->projectRepo = new \DSI\Repository\ProjectRepo();
        $this->userRepo = new \DSI\Repository\UserRepo();

        $this->user_1 = new \DSI\Entity\User();
        $this->userRepo->insert($this->user_1);
        $this->user_2 = new \DSI\Entity\User();
        $this->userRepo->insert($this->user_2);

        $this->project = new \DSI\Entity\Project();
        $this->project->setOwner($this->user_1);
        $this->projectRepo->insert($this->project);
    }

    public function tearDown()
    {
        $this->projectRepo->clearAll();
        $this->userRepo->clearAll();
        (new \DSI\Repository\ProjectMemberRequestRepo())->clearAll();
    }

    /** @test */
    public function successfulAdditionOfMemberToProject()
    {
        $this->addMemberRequestToProject->data()->userID = $this->user_2->getId();
        $this->addMemberRequestToProject->data()->projectID = $this->project->getId();
        $this->addMemberRequestToProject->exec();

        $this->assertTrue(
            (new \DSI\Repository\ProjectMemberRequestRepo())->projectHasRequestFromMember(
                $this->addMemberRequestToProject->data()->projectID,
                $this->addMemberRequestToProject->data()->userID
            )
        );
    }

    /** @test */
    public function cannotAddSameMemberTwice()
    {
        $e = null;
        $this->addMemberRequestToProject->data()->userID = $this->user_2->getId();
        $this->addMemberRequestToProject->data()->projectID = $this->project->getId();
        $this->addMemberRequestToProject->exec();

        try {
            $this->addMemberRequestToProject->data()->userID = $this->user_2->getId();
            $this->addMemberRequestToProject->data()->projectID = $this->project->getId();
            $this->addMemberRequestToProject->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('member'));
    }
}