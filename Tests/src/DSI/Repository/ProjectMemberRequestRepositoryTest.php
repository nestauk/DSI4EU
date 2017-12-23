<?php

require_once __DIR__ . '/../../../config.php';

class ProjectMemberRequestRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Repository\ProjectMemberRequestRepo */
    protected $projectMemberRequestRepository;

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
        $this->projectMemberRequestRepository = new \DSI\Repository\ProjectMemberRequestRepo();
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
        $this->projectMemberRequestRepository->clearAll();
        $this->projectsRepo->clearAll();
        $this->usersRepo->clearAll();
    }

    /** @test saveAsNew */
    public function projectMemberRequestCanBeSaved()
    {
        $projectMemberRequest = new \DSI\Entity\ProjectMemberRequest();
        $projectMemberRequest->setProject($this->project_1);
        $projectMemberRequest->setMember($this->user_1);
        $this->projectMemberRequestRepository->add($projectMemberRequest);

        $projectMemberRequest = new \DSI\Entity\ProjectMemberRequest();
        $projectMemberRequest->setProject($this->project_1);
        $projectMemberRequest->setMember($this->user_2);
        $this->projectMemberRequestRepository->add($projectMemberRequest);

        $projectMemberRequest = new \DSI\Entity\ProjectMemberRequest();
        $projectMemberRequest->setProject($this->project_2);
        $projectMemberRequest->setMember($this->user_1);
        $this->projectMemberRequestRepository->add($projectMemberRequest);

        $projectMemberRequest = new \DSI\Entity\ProjectMemberRequest();
        $projectMemberRequest->setProject($this->project_3);
        $projectMemberRequest->setMember($this->user_1);
        $this->projectMemberRequestRepository->add($projectMemberRequest);

        $this->assertCount(2, $this->projectMemberRequestRepository->getByProjectID(
            $this->project_1->getId()
        ));
    }

    /** @test saveAsNew */
    public function cannotAddSameProjectMemberRequestTwice()
    {
        $projectMemberRequest = new \DSI\Entity\ProjectMemberRequest();
        $projectMemberRequest->setProject($this->project_1);
        $projectMemberRequest->setMember($this->user_1);
        $this->projectMemberRequestRepository->add($projectMemberRequest);

        $projectMemberRequest = new \DSI\Entity\ProjectMemberRequest();
        $projectMemberRequest->setProject($this->project_1);
        $projectMemberRequest->setMember($this->user_1);
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->projectMemberRequestRepository->add($projectMemberRequest);
    }

    /** @test saveAsNew */
    public function getAllRequestMemberIDsForProject()
    {
        $projectMemberRequest = new \DSI\Entity\ProjectMemberRequest();
        $projectMemberRequest->setProject($this->project_1);
        $projectMemberRequest->setMember($this->user_1);
        $this->projectMemberRequestRepository->add($projectMemberRequest);

        $projectMemberRequest = new \DSI\Entity\ProjectMemberRequest();
        $projectMemberRequest->setProject($this->project_1);
        $projectMemberRequest->setMember($this->user_2);
        $this->projectMemberRequestRepository->add($projectMemberRequest);

        $this->assertEquals(
            [$this->user_1->getId(), $this->user_2->getId()],
            $this->projectMemberRequestRepository->getMemberIDsForProject(
                $this->project_1->getId()
            ));
    }

    /** @test saveAsNew */
    public function getAllProjectsForMember()
    {
        $projectMemberRequest = new \DSI\Entity\ProjectMemberRequest();
        $projectMemberRequest->setProject($this->project_1);
        $projectMemberRequest->setMember($this->user_1);
        $this->projectMemberRequestRepository->add($projectMemberRequest);

        $projectMemberRequest = new \DSI\Entity\ProjectMemberRequest();
        $projectMemberRequest->setProject($this->project_2);
        $projectMemberRequest->setMember($this->user_1);
        $this->projectMemberRequestRepository->add($projectMemberRequest);

        $this->assertCount(2, $this->projectMemberRequestRepository->getByMemberID(
            $this->user_1->getId()
        ));
    }

    /** @test saveAsNew */
    public function getAllProjectIDsForMember()
    {
        $projectMemberRequest = new \DSI\Entity\ProjectMemberRequest();
        $projectMemberRequest->setProject($this->project_1);
        $projectMemberRequest->setMember($this->user_1);
        $this->projectMemberRequestRepository->add($projectMemberRequest);

        $projectMemberRequest = new \DSI\Entity\ProjectMemberRequest();
        $projectMemberRequest->setProject($this->project_2);
        $projectMemberRequest->setMember($this->user_1);
        $this->projectMemberRequestRepository->add($projectMemberRequest);

        $this->assertEquals([1, 2], $this->projectMemberRequestRepository->getProjectIDsForMember(
            $this->user_1->getId()
        ));
    }

    /** @test saveAsNew */
    public function canCheckIfProjectHasMemberRequest()
    {
        $projectMemberRequest = new \DSI\Entity\ProjectMemberRequest();
        $projectMemberRequest->setProject($this->project_1);
        $projectMemberRequest->setMember($this->user_1);
        $this->projectMemberRequestRepository->add($projectMemberRequest);

        $projectMemberRequest = new \DSI\Entity\ProjectMemberRequest();
        $projectMemberRequest->setProject($this->project_2);
        $projectMemberRequest->setMember($this->user_2);
        $this->projectMemberRequestRepository->add($projectMemberRequest);

        $this->assertTrue($this->projectMemberRequestRepository->projectHasRequestFromMember(
            $this->project_1->getId(), $this->user_1->getId())
        );
        $this->assertFalse($this->projectMemberRequestRepository->projectHasRequestFromMember(
            $this->project_1->getId(), $this->user_2->getId())
        );
        $this->assertTrue($this->projectMemberRequestRepository->projectHasRequestFromMember(
            $this->project_2->getId(), $this->user_2->getId())
        );
        $this->assertFalse($this->projectMemberRequestRepository->projectHasRequestFromMember(
            $this->project_2->getId(), $this->user_1->getId())
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