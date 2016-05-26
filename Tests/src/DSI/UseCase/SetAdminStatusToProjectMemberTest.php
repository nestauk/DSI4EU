<?php

require_once __DIR__ . '/../../../config.php';

class SetAdminStatusToProjectMemberTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\SetAdminStatusToProjectMember */
    private $setStatusCmd;

    /** @var \DSI\Repository\ProjectMemberRepository */
    private $projectMemberRepo;

    /** @var \DSI\Repository\ProjectRepository */
    private $projectRepo;

    /** @var \DSI\Entity\Project */
    private $project;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $owner, $member;

    public function setUp()
    {
        $this->setStatusCmd = new \DSI\UseCase\SetAdminStatusToProjectMember();
        $this->projectMemberRepo = new \DSI\Repository\ProjectMemberRepository();
        $this->projectRepo = new \DSI\Repository\ProjectRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();

        $this->owner = new \DSI\Entity\User();
        $this->userRepo->insert($this->owner);
        $this->member = new \DSI\Entity\User();
        $this->userRepo->insert($this->member);

        $this->project = new \DSI\Entity\Project();
        $this->project->setOwner($this->owner);
        $this->projectRepo->insert($this->project);

        $addMemberToProject = new \DSI\UseCase\AddMemberToProject();
        $addMemberToProject->data()->projectID = $this->project->getId();
        $addMemberToProject->data()->userID = $this->member->getId();
        $addMemberToProject->exec();
    }

    public function tearDown()
    {
        $this->projectMemberRepo->clearAll();
        $this->projectRepo->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test */
    public function successfulSetMemberAsAdmin()
    {
        $this->setStatusCmd->data()->member = $this->member;
        $this->setStatusCmd->data()->project = $this->project;
        $this->setStatusCmd->data()->isAdmin = true;
        $this->setStatusCmd->data()->executor = $this->owner;
        $this->setStatusCmd->exec();

        $projectMember = $this->projectMemberRepo->getByProjectIDAndMemberID(
            $this->project->getId(), $this->member->getId()
        );
        $this->assertTrue($projectMember->isAdmin());
    }

    /** @test */
    public function successfulRemovalOfMemberAsAdmin()
    {
        $this->setStatusCmd->data()->member = $this->member;
        $this->setStatusCmd->data()->project = $this->project;
        $this->setStatusCmd->data()->isAdmin = false;
        $this->setStatusCmd->data()->executor = $this->owner;
        $this->setStatusCmd->exec();

        $projectMember = $this->projectMemberRepo->getByProjectIDAndMemberID(
            $this->project->getId(), $this->member->getId()
        );
        $this->assertFalse($projectMember->isAdmin());
    }

    /** @test */
    public function onlyTheOwnerCanSetAdminStatus()
    {
        $e = null;
        $this->setStatusCmd->data()->member = $this->member;
        $this->setStatusCmd->data()->project = $this->project;
        $this->setStatusCmd->data()->isAdmin = false;
        $this->setStatusCmd->data()->executor = $this->member;
        try {
            $this->setStatusCmd->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
            var_dump($e->getErrors());
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('member'));
    }
}