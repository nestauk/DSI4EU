<?php

use \DSI\UseCase\SetAdminStatusToProjectMember;

require_once __DIR__ . '/../../../config.php';

class SetAdminStatusToProjectMemberTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Repository\ProjectMemberRepository */
    private $projectMemberRepo;

    /** @var \DSI\Repository\ProjectRepository */
    private $projectRepo;

    /** @var \DSI\Entity\Project */
    private $project;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $sysadmin, $owner, $admin, $member;

    public function setUp()
    {
        $this->projectMemberRepo = new \DSI\Repository\ProjectMemberRepository();
        $this->projectRepo = new \DSI\Repository\ProjectRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();

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
        $setStatusCmd->data()->member = $this->admin;
        $setStatusCmd->data()->project = $this->project;
        $setStatusCmd->data()->isAdmin = true;
        $setStatusCmd->data()->executor = $this->owner;
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
        $setStatusCmd->data()->member = $this->member;
        $setStatusCmd->data()->project = $this->project;
        $setStatusCmd->data()->isAdmin = true;
        
        $this->setExpectedException(InvalidArgumentException::class);
        $setStatusCmd->exec();
    }

    /** @test */
    public function ownerCanSetMemberAsAdmin()
    {
        $this->addMemberToProject($this->project, $this->member);

        $setStatusCmd = new SetAdminStatusToProjectMember();
        $setStatusCmd->data()->member = $this->member;
        $setStatusCmd->data()->project = $this->project;
        $setStatusCmd->data()->isAdmin = true;
        $setStatusCmd->data()->executor = $this->owner;
        $setStatusCmd->exec();

        $projectMember = $this->projectMemberRepo->getByProjectIDAndMemberID(
            $this->project->getId(), $this->member->getId()
        );
        $this->assertTrue($projectMember->isAdmin());
    }

    /** @test */
    public function adminCanSetMemberAsAdmin()
    {
        $this->addMemberToProject($this->project, $this->member);

        $setStatusCmd = new SetAdminStatusToProjectMember();
        $setStatusCmd->data()->member = $this->member;
        $setStatusCmd->data()->project = $this->project;
        $setStatusCmd->data()->isAdmin = true;
        $setStatusCmd->data()->executor = $this->admin;
        $setStatusCmd->exec();

        $projectMember = $this->projectMemberRepo->getByProjectIDAndMemberID(
            $this->project->getId(), $this->member->getId()
        );
        $this->assertTrue($projectMember->isAdmin());
    }

    /** @test */
    public function sysAdminCanSetMemberAsAdmin()
    {
        $this->addMemberToProject($this->project, $this->member);

        $setStatusCmd = new SetAdminStatusToProjectMember();
        $setStatusCmd->data()->member = $this->member;
        $setStatusCmd->data()->project = $this->project;
        $setStatusCmd->data()->isAdmin = true;
        $setStatusCmd->data()->executor = $this->sysadmin;
        $setStatusCmd->exec();

        $projectMember = $this->projectMemberRepo->getByProjectIDAndMemberID(
            $this->project->getId(), $this->member->getId()
        );
        $this->assertTrue($projectMember->isAdmin());
    }

    /** @test */
    public function successfulRemovalOfMemberAsAdmin()
    {
        $this->addMemberToProject($this->project, $this->member);

        $setStatusCmd = new SetAdminStatusToProjectMember();
        $setStatusCmd->data()->member = $this->member;
        $setStatusCmd->data()->project = $this->project;
        $setStatusCmd->data()->isAdmin = false;
        $setStatusCmd->data()->executor = $this->owner;
        $setStatusCmd->exec();

        $projectMember = $this->projectMemberRepo->getByProjectIDAndMemberID(
            $this->project->getId(), $this->member->getId()
        );
        $this->assertFalse($projectMember->isAdmin());
    }

    /** @test */
    public function otherUsersCannotSetAdminStatus()
    {
        $this->addMemberToProject($this->project, $this->member);

        $e = null;
        $setStatusCmd = new SetAdminStatusToProjectMember();
        $setStatusCmd->data()->member = $this->member;
        $setStatusCmd->data()->project = $this->project;
        $setStatusCmd->data()->isAdmin = false;
        $setStatusCmd->data()->executor = $this->member;
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
        $setStatusCmd->data()->member = $this->member;
        $setStatusCmd->data()->project = $this->project;
        $setStatusCmd->data()->isAdmin = true;
        $setStatusCmd->data()->executor = $this->sysadmin;
        $setStatusCmd->exec();

        $projectMember = $this->projectMemberRepo->getByProjectIDAndMemberID(
            $this->project->getId(), $this->member->getId()
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