<?php

require_once __DIR__ . '/../../../config.php';

class RemoveMemberFromProjectTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\AddMemberToProject */
    private $addMemberToProjectCommand;

    /** @var \DSI\UseCase\RemoveMemberFromProject */
    private $removeMemberFromProjectCommand;

    /** @var \DSI\Repository\ProjectMemberRepository */
    private $projectMemberRepo;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Repository\ProjectRepository */
    private $projectRepo;

    /** @var \DSI\Entity\User */
    private $user;

    /** @var \DSI\Entity\Project */
    private $project;

    public function setUp()
    {
        $this->addMemberToProjectCommand = new \DSI\UseCase\AddMemberToProject();
        $this->removeMemberFromProjectCommand = new \DSI\UseCase\RemoveMemberFromProject();
        $this->projectMemberRepo = new \DSI\Repository\ProjectMemberRepository();
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
        $this->projectMemberRepo->clearAll();
        $this->projectRepo->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test */
    public function successfulRemoveMemberFromProject()
    {
        $this->addMemberToProjectCommand->setUser($this->user);
        $this->addMemberToProjectCommand->setProject($this->project);
        $this->addMemberToProjectCommand->exec();

        $this->removeMemberFromProjectCommand->setUser($this->user);
        $this->removeMemberFromProjectCommand->setProject($this->project);
        $this->removeMemberFromProjectCommand->exec();

        $this->assertFalse(
            $this->projectMemberRepo->projectIDHasMemberID(
                $this->project->getId(),
                $this->user->getId()
            )
        );
    }

    /** @test */
    public function cannotRemoveIfUserIsNotAMember()
    {
        $e = null;

        try {
            $this->removeMemberFromProjectCommand->setUser($this->user);
            $this->removeMemberFromProjectCommand->setProject($this->project);
            $this->removeMemberFromProjectCommand->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('member'));
    }
}