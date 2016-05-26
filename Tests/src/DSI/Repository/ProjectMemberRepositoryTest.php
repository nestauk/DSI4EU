<?php

require_once __DIR__ . '/../../../config.php';

class ProjectMemberRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Repository\ProjectMemberRepository */
    protected $projectMemberRepo;

    /** @var \DSI\Repository\ProjectRepository */
    protected $projectsRepo;

    /** @var \DSI\Repository\UserRepository */
    protected $usersRepo;

    /** @var \DSI\Entity\Project */
    protected $project_1, $project_2, $project_3;

    /** @var \DSI\Entity\User */
    protected $user_1, $user_2, $user_3;

    public function setUp()
    {
        $this->projectMemberRepo = new \DSI\Repository\ProjectMemberRepository();
        $this->projectsRepo = new \DSI\Repository\ProjectRepository();
        $this->usersRepo = new \DSI\Repository\UserRepository();

        $this->user_1 = $this->createUser();
        $this->user_2 = $this->createUser();
        $this->user_3 = $this->createUser();

        $this->project_1 = $this->createProject($this->user_1);
        $this->project_2 = $this->createProject($this->user_2);
        $this->project_3 = $this->createProject($this->user_3);
    }

    public function tearDown()
    {
        $this->projectMemberRepo->clearAll();
        $this->projectsRepo->clearAll();
        $this->usersRepo->clearAll();
    }

    /** @test saveAsNew */
    public function projectMemberCanBeSaved()
    {
        $projectMember = new \DSI\Entity\ProjectMember();
        $projectMember->setProject($this->project_1);
        $projectMember->setMember($this->user_1);
        $this->projectMemberRepo->insert($projectMember);

        $projectMember = new \DSI\Entity\ProjectMember();
        $projectMember->setProject($this->project_1);
        $projectMember->setMember($this->user_2);
        $this->projectMemberRepo->insert($projectMember);

        $projectMember = new \DSI\Entity\ProjectMember();
        $projectMember->setProject($this->project_2);
        $projectMember->setMember($this->user_1);
        $this->projectMemberRepo->insert($projectMember);

        $projectMember = new \DSI\Entity\ProjectMember();
        $projectMember->setProject($this->project_3);
        $projectMember->setMember($this->user_1);
        $this->projectMemberRepo->insert($projectMember);

        $this->assertCount(2, $this->projectMemberRepo->getByProjectID(
            $this->project_1->getId()
        ));
    }

    /** @test saveAsNew */
    public function cannotAddSameProjectMemberTwice()
    {
        $projectMember = new \DSI\Entity\ProjectMember();
        $projectMember->setProject($this->project_1);
        $projectMember->setMember($this->user_1);
        $this->projectMemberRepo->insert($projectMember);

        $projectMember = new \DSI\Entity\ProjectMember();
        $projectMember->setProject($this->project_1);
        $projectMember->setMember($this->user_1);
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->projectMemberRepo->insert($projectMember);
    }

    /** @test saveAsNew */
    public function getAllMemberIDsForProject()
    {
        $projectMember = new \DSI\Entity\ProjectMember();
        $projectMember->setProject($this->project_1);
        $projectMember->setMember($this->user_1);
        $this->projectMemberRepo->insert($projectMember);

        $projectMember = new \DSI\Entity\ProjectMember();
        $projectMember->setProject($this->project_1);
        $projectMember->setMember($this->user_2);
        $this->projectMemberRepo->insert($projectMember);

        $this->assertEquals(
            [$this->user_1->getId(), $this->user_2->getId()],
            $this->projectMemberRepo->getMemberIDsForProject(
                $this->project_1->getId()
            ));
    }

    /** @test saveAsNew */
    public function getAllProjectsForMember()
    {
        $projectMember = new \DSI\Entity\ProjectMember();
        $projectMember->setProject($this->project_1);
        $projectMember->setMember($this->user_1);
        $this->projectMemberRepo->insert($projectMember);

        $projectMember = new \DSI\Entity\ProjectMember();
        $projectMember->setProject($this->project_2);
        $projectMember->setMember($this->user_1);
        $this->projectMemberRepo->insert($projectMember);

        $this->assertCount(2, $this->projectMemberRepo->getByMemberID(
            $this->user_1->getId()
        ));
    }

    /** @test */
    public function retainThatAMemberIsAdmin()
    {
        $projectMember = new \DSI\Entity\ProjectMember();
        $projectMember->setProject($this->project_1);
        $projectMember->setMember($this->user_1);
        $projectMember->setIsAdmin(true);
        $this->projectMemberRepo->insert($projectMember);

        $projectMember = $this->projectMemberRepo->getByMemberID(
            $this->user_1->getId()
        )[0];
        $this->assertTrue($projectMember->isAdmin());
    }

    /** @test saveAsNew */
    public function getAllProjectIDsForMember()
    {
        $projectMember = new \DSI\Entity\ProjectMember();
        $projectMember->setProject($this->project_1);
        $projectMember->setMember($this->user_1);
        $this->projectMemberRepo->insert($projectMember);

        $projectMember = new \DSI\Entity\ProjectMember();
        $projectMember->setProject($this->project_2);
        $projectMember->setMember($this->user_1);
        $this->projectMemberRepo->insert($projectMember);

        $this->assertEquals([1, 2], $this->projectMemberRepo->getProjectIDsForMember(
            $this->user_1->getId()
        ));
    }

    /** @test saveAsNew */
    public function canCheckIfProjectHasMember()
    {
        $projectMember = new \DSI\Entity\ProjectMember();
        $projectMember->setProject($this->project_1);
        $projectMember->setMember($this->user_1);
        $this->projectMemberRepo->insert($projectMember);

        $projectMember = new \DSI\Entity\ProjectMember();
        $projectMember->setProject($this->project_2);
        $projectMember->setMember($this->user_2);
        $this->projectMemberRepo->insert($projectMember);

        $this->assertTrue($this->projectMemberRepo->projectHasMember(
            $this->project_1->getId(), $this->user_1->getId())
        );
        $this->assertFalse($this->projectMemberRepo->projectHasMember(
            $this->project_1->getId(), $this->user_2->getId())
        );
        $this->assertTrue($this->projectMemberRepo->projectHasMember(
            $this->project_2->getId(), $this->user_2->getId())
        );
        $this->assertFalse($this->projectMemberRepo->projectHasMember(
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