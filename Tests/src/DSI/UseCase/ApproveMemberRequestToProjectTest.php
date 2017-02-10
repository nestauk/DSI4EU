<?php

require_once __DIR__ . '/../../../config.php';

class ApproveMemberRequestToProjectTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\AddMemberRequestToProject */
    private $addMemberRequestToProject;

    /** @var \DSI\Repository\ProjectMemberRequestRepository */
    private $projectMemberRequestRepo;

    /** @var \DSI\Repository\ProjectMemberRepository */
    private $projectMemberRepo;

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
        $this->projectMemberRequestRepo = new \DSI\Repository\ProjectMemberRequestRepository();
        $this->projectMemberRepo = new \DSI\Repository\ProjectMemberRepository();
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
        $this->projectMemberRequestRepo->clearAll();
        $this->projectMemberRepo->clearAll();
        $this->projectRepo->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test */
    public function cannotApproveAnNonexistentRequest()
    {
        $e = null;

        $approveCmd = new \DSI\UseCase\ApproveMemberRequestToProject();
        $approveCmd->data()->projectID = $this->project->getId();
        $approveCmd->data()->userID = $this->user_2->getId();
        try {
            $approveCmd->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('member'));
    }

    /** @test */
    public function successfulApprovalOfMemberRequestToProject_removesTheRequest()
    {
        $this->addProjectMemberRequest($this->project->getId(), $this->user_2->getId());
        $this->approveRequest($this->project->getId(), $this->user_2->getId());

        $this->assertFalse(
            $this->projectMemberRequestRepo->projectHasRequestFromMember($this->project->getId(), $this->user_2->getId())
        );
    }

    /** @test */
    public function successfulApprovalOfMemberRequestToProject_addsMemberToProject()
    {
        $this->addProjectMemberRequest($this->project->getId(), $this->user_2->getId());
        $this->approveRequest($this->project->getId(), $this->user_2->getId());

        $this->assertTrue(
            $this->projectMemberRepo->projectHasMember($this->project, $this->user_2)
        );
    }


    private function addProjectMemberRequest($projectID, $userID)
    {
        $this->addMemberRequestToProject = new \DSI\UseCase\AddMemberRequestToProject();
        $this->addMemberRequestToProject->data()->userID = $userID;
        $this->addMemberRequestToProject->data()->projectID = $projectID;
        $this->addMemberRequestToProject->exec();
    }

    private function approveRequest($projectID, $userID)
    {
        $approveCmd = new \DSI\UseCase\ApproveMemberRequestToProject();
        $approveCmd->data()->projectID = $projectID;
        $approveCmd->data()->userID = $userID;
        $approveCmd->exec();
    }
}