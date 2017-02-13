<?php

require_once __DIR__ . '/../../../config.php';

class AddMemberInvitationToProjectTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\AddMemberInvitationToProject */
    private $addMemberInvitationToProject;

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
        $this->addMemberInvitationToProject = new \DSI\UseCase\AddMemberInvitationToProject();
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
        (new \DSI\Repository\ProjectMemberInvitationRepository())->clearAll();
    }

    /** @test */
    public function successfulAdditionOfMemberToProject()
    {
        $this->addMemberInvitationToProject->setUser($this->user_2);
        $this->addMemberInvitationToProject->setProject($this->project);
        $this->addMemberInvitationToProject->exec();

        $this->assertTrue(
            (new \DSI\Repository\ProjectMemberInvitationRepository())->userHasBeenInvitedToProject(
                $this->user_2,
                $this->project
            )
        );
    }

    /** @test */
    public function cannotAddSameMemberTwice()
    {
        $e = null;
        $this->addMemberInvitationToProject->setUser($this->user_2);
        $this->addMemberInvitationToProject->setProject($this->project);
        $this->addMemberInvitationToProject->exec();

        try {
            $this->addMemberInvitationToProject->setUser($this->user_2);
            $this->addMemberInvitationToProject->setProject($this->project);
            $this->addMemberInvitationToProject->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('member'));
    }
}