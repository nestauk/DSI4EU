<?php

require_once __DIR__ . '/../../../config.php';

class AddMemberToProjectTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\AddMemberToProject */
    private $addMemberToProject;

    /** @var \DSI\Repository\ProjectRepository */
    private $projectRepo;

    /** @var \DSI\Entity\Project */
    private $project;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user_1, $user_2;

    public function setUp()
    {
        $this->addMemberToProject = new \DSI\UseCase\AddMemberToProject();
        $this->projectRepo = new \DSI\Repository\ProjectRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();

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
        (new \DSI\Repository\ProjectMemberRepository())->clearAll();
    }

    /** @test */
    public function successfulAdditionOfMemberToProject()
    {
        $this->addMemberToProject->data()->userID = $this->user_2->getId();
        $this->addMemberToProject->data()->projectID = $this->project->getId();
        $this->addMemberToProject->exec();

        $this->assertTrue(
            (new \DSI\Repository\ProjectMemberRepository())->projectIDHasMemberID(
                $this->addMemberToProject->data()->projectID,
                $this->addMemberToProject->data()->userID
            )
        );
    }

    /** @test */
    public function cannotAddSameMemberTwice()
    {
        $e = null;
        $this->addMemberToProject->data()->userID = $this->user_2->getId();
        $this->addMemberToProject->data()->projectID = $this->project->getId();
        $this->addMemberToProject->exec();

        try {
            $this->addMemberToProject->data()->userID = $this->user_2->getId();
            $this->addMemberToProject->data()->projectID = $this->project->getId();
            $this->addMemberToProject->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('member'));
    }
}