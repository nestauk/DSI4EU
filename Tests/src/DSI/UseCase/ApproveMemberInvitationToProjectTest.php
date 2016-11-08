<?php

require_once __DIR__ . '/../../../config.php';

class ApproveMemberInvitationToProjectTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\AddMemberInvitationToProject */
    private $addMemberInvitationToProject;

    /** @var \DSI\Repository\ProjectMemberInvitationRepository */
    private $projectMemberInvitationRepo;

    /** @var \DSI\Repository\ProjectMemberRepository */
    private $projectMemberRepo;

    /** @var \DSI\Repository\ProjectRepository */
    private $projectRepo;

    /** @var \DSI\Entity\Project */
    private $project;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $projectOwner,
        $invitedUser;

    public function setUp()
    {
        $this->projectMemberInvitationRepo = new \DSI\Repository\ProjectMemberInvitationRepository();
        $this->projectMemberRepo = new \DSI\Repository\ProjectMemberRepository();
        $this->projectRepo = new \DSI\Repository\ProjectRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();

        $this->projectOwner = new \DSI\Entity\User();
        $this->userRepo->insert($this->projectOwner);

        $this->invitedUser = new \DSI\Entity\User();
        $this->userRepo->insert($this->invitedUser);

        $this->project = new \DSI\Entity\Project();
        $this->project->setOwner($this->projectOwner);
        $this->projectRepo->insert($this->project);
    }

    public function tearDown()
    {
        $this->projectMemberInvitationRepo->clearAll();
        $this->projectMemberRepo->clearAll();
        $this->projectRepo->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test */
    public function cannotExecuteWithoutAnExecutor()
    {
        $approveCmd = new \DSI\UseCase\ApproveMemberInvitationToProject();
        $this->setExpectedException(InvalidArgumentException::class);
        $approveCmd->exec();
    }

    /** @test */
    public function cannotApproveAnNonexistentInvitation()
    {
        $e = null;

        $approveCmd = new \DSI\UseCase\ApproveMemberInvitationToProject();
        $approveCmd->data()->executor = $this->invitedUser;
        $approveCmd->data()->projectID = $this->project->getId();
        $approveCmd->data()->userID = $this->invitedUser->getId();
        try {
            $approveCmd->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('member'));
    }

    /** @test */
    public function onlyTheInvitedUserCanApproveTheInvitation()
    {
        $e = null;
        try {
            $this->addProjectMemberInvitation($this->project->getId(), $this->invitedUser->getId());

            $approveCmd = new \DSI\UseCase\ApproveMemberInvitationToProject();
            $approveCmd->data()->executor = $this->projectOwner;
            $approveCmd->data()->projectID = $this->project->getId();
            $approveCmd->data()->userID = $this->invitedUser->getId();
            $approveCmd->exec();

        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('executor'));
    }

    /** @test */
    public function successfulApprovalOfMemberInvitationToProject_removesTheInvitation()
    {
        try {
            $this->addProjectMemberInvitation($this->project->getId(), $this->invitedUser->getId());
            $this->approveInvitation($this->project->getId(), $this->invitedUser->getId());
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertFalse(
            $this->projectMemberInvitationRepo->memberHasInvitationToProject($this->invitedUser->getId(), $this->project->getId())
        );
    }

    /** @test */
    public function successfulApprovalOfMemberInvitationToProject_addsMemberToProject()
    {
        $this->addProjectMemberInvitation($this->project->getId(), $this->invitedUser->getId());
        $this->approveInvitation($this->project->getId(), $this->invitedUser->getId());

        $this->assertTrue(
            $this->projectMemberRepo->projectIDHasMemberID($this->project->getId(), $this->invitedUser->getId())
        );
    }

    private function addProjectMemberInvitation($projectID, $userID)
    {
        $this->addMemberInvitationToProject = new \DSI\UseCase\AddMemberInvitationToProject();
        $this->addMemberInvitationToProject->data()->userID = $userID;
        $this->addMemberInvitationToProject->data()->projectID = $projectID;
        $this->addMemberInvitationToProject->exec();
    }

    private function approveInvitation($projectID, $userID)
    {
        $approveCmd = new \DSI\UseCase\ApproveMemberInvitationToProject();
        $approveCmd->data()->executor = $this->invitedUser;
        $approveCmd->data()->projectID = $projectID;
        $approveCmd->data()->userID = $userID;
        $approveCmd->exec();
    }
}