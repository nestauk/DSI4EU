<?php

use \DSI\UseCase\SetAdminStatusToProjectMember;

require_once __DIR__ . '/../../../config.php';

class SetAdminStatusToProjectMemberTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Repository\ProjectMemberRepo */
    private $projectMemberRepo;

    /** @var \DSI\Repository\ProjectRepo */
    private $projectRepo;

    /** @var \DSI\Entity\Project */
    private $project;

    /** @var \DSI\Repository\UserRepo */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $sysadmin, $owner, $admin, $member;

    public function setUp()
    {
        $this->projectMemberRepo = new \DSI\Repository\ProjectMemberRepo();
        $this->projectRepo = new \DSI\Repository\ProjectRepo();
        $this->userRepo = new \DSI\Repository\UserRepo();

        $this->owner = new \DSI\Entity\User();
        $this->userRepo->insert($this->owner);

        $this->admin = new \DSI\Entity\User();
        $this->userRepo->insert($this->admin);

        $this->member = new \DSI\Entity\User();
        $this->userRepo->insert($this->member);

        $this->sysadmin = new \DSI\Entity\User();
        $this->sysadmin->setRole('sys-admin');
        $this->userRepo->insert($this->sysadmin);

        $this->project = new \DSI\Entity\Project();
        $this->project->setOwner($this->owner);
        $this->projectRepo->insert($this->project);

        $this->addMemberToProject($this->project, $this->admin);

        $setStatusCmd = new SetAdminStatusToProjectMember();
        $setStatusCmd->setMember($this->admin);
        $setStatusCmd->setProject($this->project);
        $setStatusCmd->setIsAdmin(true);
        $setStatusCmd->setExecutor($this->owner);
        $setStatusCmd->exec();
    }

    public function tearDown()
    {
        $this->projectMemberRepo->clearAll();
        $this->projectRepo->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test */
    public function executorMustBeSent()
    {
        $setStatusCmd = new SetAdminStatusToProjectMember();
        $setStatusCmd->setMember($this->member);
        $setStatusCmd->setProject($this->project);
        $setStatusCmd->setIsAdmin(true);

        $this->setExpectedException(InvalidArgumentException::class);
        $setStatusCmd->exec();
    }

    /** @test */
    public function ownerCanSetMemberAsAdmin()
    {
        $this->addMemberToProject($this->project, $this->member);

        $setStatusCmd = new SetAdminStatusToProjectMember();
        $setStatusCmd->setMember($this->member);
        $setStatusCmd->setProject($this->project);
        $setStatusCmd->setIsAdmin(true);
        $setStatusCmd->setExecutor($this->owner);
        $setStatusCmd->exec();

        $projectMember = $this->projectMemberRepo->getByProjectAndMember(
            $this->project, $this->member
        );
        $this->assertTrue($projectMember->isAdmin());
    }

    /** @test */
    public function adminCannotSetMemberAsAdmin()
    {
        $this->addMemberToProject($this->project, $this->member);

        $e = null;
        $setStatusCmd = new SetAdminStatusToProjectMember();
        $setStatusCmd->setMember($this->member);
        $setStatusCmd->setProject($this->project);
        $setStatusCmd->setIsAdmin(true);
        $setStatusCmd->setExecutor($this->admin);
        try {
            $setStatusCmd->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('member'));

        $projectMember = $this->projectMemberRepo->getByProjectAndMember(
            $this->project, $this->member
        );
        $this->assertFalse($projectMember->isAdmin());
    }

    /** @test */
    public function sysAdminCanSetMemberAsAdmin()
    {
        $this->addMemberToProject($this->project, $this->member);

        $setStatusCmd = new SetAdminStatusToProjectMember();
        $setStatusCmd->setMember($this->member);
        $setStatusCmd->setProject($this->project);
        $setStatusCmd->setIsAdmin(true);
        $setStatusCmd->setExecutor($this->sysadmin);
        $setStatusCmd->exec();

        $projectMember = $this->projectMemberRepo->getByProjectAndMember(
            $this->project, $this->member
        );
        $this->assertTrue($projectMember->isAdmin());
    }

    /** @test */
    public function successfulRemovalOfMemberAsAdmin()
    {
        $this->addMemberToProject($this->project, $this->member);

        $setStatusCmd = new SetAdminStatusToProjectMember();
        $setStatusCmd->setMember($this->member);
        $setStatusCmd->setProject($this->project);
        $setStatusCmd->setIsAdmin(false);
        $setStatusCmd->setExecutor($this->owner);
        $setStatusCmd->exec();

        $projectMember = $this->projectMemberRepo->getByProjectAndMember(
            $this->project, $this->member
        );
        $this->assertFalse($projectMember->isAdmin());
    }

    /** @test */
    public function otherUsersCannotSetAdminStatus()
    {
        $this->addMemberToProject($this->project, $this->member);

        $e = null;
        $setStatusCmd = new SetAdminStatusToProjectMember();
        $setStatusCmd->setMember($this->member);
        $setStatusCmd->setProject($this->project);
        $setStatusCmd->setIsAdmin(false);
        $setStatusCmd->setExecutor($this->member);
        try {
            $setStatusCmd->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('member'));
    }

    /** @test */
    public function userIsAutomaticallyAddedAsMember()
    {
        $setStatusCmd = new SetAdminStatusToProjectMember();
        $setStatusCmd->setMember($this->member);
        $setStatusCmd->setProject($this->project);
        $setStatusCmd->setIsAdmin(true);
        $setStatusCmd->setExecutor($this->sysadmin);
        $setStatusCmd->exec();

        $projectMember = $this->projectMemberRepo->getByProjectAndMember(
            $this->project, $this->member
        );
        $this->assertTrue($projectMember->isAdmin());
    }


    /**
     * @param \DSI\Entity\Project $project
     * @param \DSI\Entity\User $user
     */
    private function addMemberToProject(\DSI\Entity\Project $project, \DSI\Entity\User $user)
    {
        $addMemberToProject = new \DSI\UseCase\AddMemberToProject();
        $addMemberToProject->setProject($project);
        $addMemberToProject->setUser($user);
        $addMemberToProject->exec();
    }
}