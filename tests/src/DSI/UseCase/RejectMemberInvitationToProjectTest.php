<?php

require_once __DIR__ . '/../../../config.php';

class RejectMemberInvitationToProjectTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\AddMemberInvitationToProject */
    private $addMemberInvitationToProject;

    /** @var \DSI\Repository\ProjectMemberInvitationRepo */
    private $projectMemberInvitationRepo;

    /** @var \DSI\Repository\ProjectMemberRepo */
    private $projectMemberRepo;

    /** @var \DSI\Repository\ProjectRepo */
    private $projectRepo;

    /** @var \DSI\Entity\Project */
    private $project;

    /** @var \DSI\Repository\UserRepo */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $projectOwner,
        $invitedUser;

    public function setUp()
    {
        $this->projectMemberInvitationRepo = new \DSI\Repository\ProjectMemberInvitationRepo();
        $this->projectMemberRepo = new \DSI\Repository\ProjectMemberRepo();
        $this->projectRepo = new \DSI\Repository\ProjectRepo();
        $this->userRepo = new \DSI\Repository\UserRepo();

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
        $rejectCmd = new \DSI\UseCase\RejectMemberInvitationToProject();
        $this->setExpectedException(InvalidArgumentException::class);
        $rejectCmd->exec();
    }

    /** @test */
    public function cannotRejectAnNonexistentInvitation()
    {
        $e = null;

        $rejectCmd = new \DSI\UseCase\RejectMemberInvitationToProject();
        $rejectCmd->data()->executor = $this->invitedUser;
        $rejectCmd->data()->projectID = $this->project->getId();
        $rejectCmd->data()->userID = $this->invitedUser->getId();
        try {
            $rejectCmd->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('member'));
    }

    /** @test */
    public function onlyTheInvitedUserCanRejectTheInvitation()
    {
        $e = null;
        try {
            $this->addProjectMemberInvitation($this->project->getId(), $this->invitedUser->getId());

            $rejectCmd = new \DSI\UseCase\RejectMemberInvitationToProject();
            $rejectCmd->data()->executor = $this->projectOwner;
            $rejectCmd->data()->projectID = $this->project->getId();
            $rejectCmd->data()->userID = $this->invitedUser->getId();
            $rejectCmd->exec();

        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('executor'));
    }

    /** @test */
    public function successfulRejectionOfMemberInvitationToProject_removesTheInvitation()
    {
        try {
            $this->addProjectMemberInvitation($this->project->getId(), $this->invitedUser->getId());
            $this->rejectInvitation($this->project->getId(), $this->invitedUser->getId());
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertFalse(
            $this->projectMemberInvitationRepo->userHasBeenInvitedToProject(
                $this->invitedUser, $this->project
            )
        );
    }

    /** @test */
    public function successfulRejectionOfMemberInvitationToProject_doesNotAddMemberToProject()
    {
        $this->addProjectMemberInvitation($this->project->getId(), $this->invitedUser->getId());
        $this->rejectInvitation($this->project->getId(), $this->invitedUser->getId());

        $this->assertFalse(
            $this->projectMemberRepo->projectHasMember($this->project, $this->invitedUser)
        );
    }

    private function addProjectMemberInvitation($projectID, $userID)
    {
        $this->addMemberInvitationToProject = new \DSI\UseCase\AddMemberInvitationToProject();
        $this->addMemberInvitationToProject->setUserID($userID);
        $this->addMemberInvitationToProject->setProjectID($projectID);
        $this->addMemberInvitationToProject->exec();
    }

    private function rejectInvitation($projectID, $userID)
    {
        $rejectCmd = new \DSI\UseCase\RejectMemberInvitationToProject();
        $rejectCmd->data()->executor = $this->invitedUser;
        $rejectCmd->data()->projectID = $projectID;
        $rejectCmd->data()->userID = $userID;
        $rejectCmd->exec();
    }
}