<?php

require_once __DIR__ . '/../../../config.php';

class ProjectMemberInvitationRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Repository\ProjectMemberInvitationRepo */
    protected $projectMemberInvitationRepository;

    /** @var \DSI\Repository\ProjectRepo */
    protected $projectsRepo;

    /** @var \DSI\Repository\UserRepo */
    protected $usersRepo;

    /** @var \DSI\Entity\Project */
    protected $project_1, $project_2, $project_3;

    /** @var \DSI\Entity\User */
    protected $user_1, $user_2, $user_3;

    public function setUp()
    {
        $this->projectMemberInvitationRepository = new \DSI\Repository\ProjectMemberInvitationRepo();
        $this->projectsRepo = new \DSI\Repository\ProjectRepo();
        $this->usersRepo = new \DSI\Repository\UserRepo();

        $this->user_1 = $this->createUser();
        $this->user_2 = $this->createUser();
        $this->user_3 = $this->createUser();

        $this->project_1 = $this->createProject($this->user_1);
        $this->project_2 = $this->createProject($this->user_2);
        $this->project_3 = $this->createProject($this->user_3);
    }

    public function tearDown()
    {
        $this->projectMemberInvitationRepository->clearAll();
        $this->projectsRepo->clearAll();
        $this->usersRepo->clearAll();
    }

    /** @test saveAsNew */
    public function projectMemberInvitationCanBeSaved()
    {
        $projectMemberInvitation = new \DSI\Entity\ProjectMemberInvitation();
        $projectMemberInvitation->setProject($this->project_1);
        $projectMemberInvitation->setMember($this->user_1);
        $this->projectMemberInvitationRepository->add($projectMemberInvitation);

        $projectMemberInvitation = new \DSI\Entity\ProjectMemberInvitation();
        $projectMemberInvitation->setProject($this->project_1);
        $projectMemberInvitation->setMember($this->user_2);
        $this->projectMemberInvitationRepository->add($projectMemberInvitation);

        $projectMemberInvitation = new \DSI\Entity\ProjectMemberInvitation();
        $projectMemberInvitation->setProject($this->project_2);
        $projectMemberInvitation->setMember($this->user_1);
        $this->projectMemberInvitationRepository->add($projectMemberInvitation);

        $projectMemberInvitation = new \DSI\Entity\ProjectMemberInvitation();
        $projectMemberInvitation->setProject($this->project_3);
        $projectMemberInvitation->setMember($this->user_1);
        $this->projectMemberInvitationRepository->add($projectMemberInvitation);

        $this->assertCount(2, $this->projectMemberInvitationRepository->getByProject(
            $this->project_1
        ));
    }

    /** @test saveAsNew */
    public function cannotAddSameProjectMemberInvitationTwice()
    {
        $projectMemberInvitation = new \DSI\Entity\ProjectMemberInvitation();
        $projectMemberInvitation->setProject($this->project_1);
        $projectMemberInvitation->setMember($this->user_1);
        $this->projectMemberInvitationRepository->add($projectMemberInvitation);

        $projectMemberInvitation = new \DSI\Entity\ProjectMemberInvitation();
        $projectMemberInvitation->setProject($this->project_1);
        $projectMemberInvitation->setMember($this->user_1);
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->projectMemberInvitationRepository->add($projectMemberInvitation);
    }

    /** @test saveAsNew */
    public function getAllProjectsForMember()
    {
        $projectMemberInvitation = new \DSI\Entity\ProjectMemberInvitation();
        $projectMemberInvitation->setProject($this->project_1);
        $projectMemberInvitation->setMember($this->user_1);
        $this->projectMemberInvitationRepository->add($projectMemberInvitation);

        $projectMemberInvitation = new \DSI\Entity\ProjectMemberInvitation();
        $projectMemberInvitation->setProject($this->project_2);
        $projectMemberInvitation->setMember($this->user_1);
        $this->projectMemberInvitationRepository->add($projectMemberInvitation);

        $this->assertCount(2, $this->projectMemberInvitationRepository->getByMemberID(
            $this->user_1->getId()
        ));
    }

    /** @test saveAsNew */
    public function getAllProjectIDsForMember()
    {
        $projectMemberInvitation = new \DSI\Entity\ProjectMemberInvitation();
        $projectMemberInvitation->setProject($this->project_1);
        $projectMemberInvitation->setMember($this->user_1);
        $this->projectMemberInvitationRepository->add($projectMemberInvitation);

        $projectMemberInvitation = new \DSI\Entity\ProjectMemberInvitation();
        $projectMemberInvitation->setProject($this->project_2);
        $projectMemberInvitation->setMember($this->user_1);
        $this->projectMemberInvitationRepository->add($projectMemberInvitation);

        $this->assertEquals([1, 2], $this->projectMemberInvitationRepository->getProjectIDsForMember(
            $this->user_1->getId()
        ));
    }

    /** @test saveAsNew */
    public function canCheckIfMemberHasInvitationToProject()
    {
        $projectMemberInvitation = new \DSI\Entity\ProjectMemberInvitation();
        $projectMemberInvitation->setProject($this->project_1);
        $projectMemberInvitation->setMember($this->user_1);
        $this->projectMemberInvitationRepository->add($projectMemberInvitation);

        $projectMemberInvitation = new \DSI\Entity\ProjectMemberInvitation();
        $projectMemberInvitation->setProject($this->project_2);
        $projectMemberInvitation->setMember($this->user_2);
        $this->projectMemberInvitationRepository->add($projectMemberInvitation);

        $this->assertTrue($this->projectMemberInvitationRepository->userHasBeenInvitedToProject(
            $this->user_1, $this->project_1)
        );
        $this->assertFalse($this->projectMemberInvitationRepository->userHasBeenInvitedToProject(
            $this->user_2, $this->project_1)
        );
        $this->assertTrue($this->projectMemberInvitationRepository->userHasBeenInvitedToProject(
            $this->user_2, $this->project_2)
        );
        $this->assertFalse($this->projectMemberInvitationRepository->userHasBeenInvitedToProject(
            $this->user_1, $this->project_2)
        );
    }

    private function createProject(\DSI\Entity\User $user)
    {
        $project = new \DSI\Entity\Project();
        $project->setOwner($user);
        $this->projectsRepo->insert($project);
        return $project;
    }

    private function createUser()
    {
        $user = new \DSI\Entity\User();
        $this->usersRepo->insert($user);
        return $user;
    }
}