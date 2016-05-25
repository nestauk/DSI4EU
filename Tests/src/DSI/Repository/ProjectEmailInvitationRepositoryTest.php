<?php

require_once __DIR__ . '/../../../config.php';

class ProjectEmailInvitationRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Repository\ProjectEmailInvitationRepository */
    protected $projectEmailInvitationRepo;

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
        $this->projectEmailInvitationRepo = new \DSI\Repository\ProjectEmailInvitationRepository();
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
        $this->projectEmailInvitationRepo->clearAll();
        $this->projectsRepo->clearAll();
        $this->usersRepo->clearAll();
    }

    /** @test saveAsNew */
    public function projectEmailInvitationCanBeSaved()
    {
        $projectMember = new \DSI\Entity\ProjectEmailInvitation();
        $projectMember->setProject($this->project_1);
        $projectMember->setByUser($this->user_1);
        $projectMember->setEmail('test@example.org');
        $this->projectEmailInvitationRepo->add($projectMember);

        $projectMember = new \DSI\Entity\ProjectEmailInvitation();
        $projectMember->setProject($this->project_1);
        $projectMember->setByUser($this->user_2);
        $projectMember->setEmail('test2@example.org');
        $this->projectEmailInvitationRepo->add($projectMember);

        $projectMember = new \DSI\Entity\ProjectEmailInvitation();
        $projectMember->setProject($this->project_2);
        $projectMember->setByUser($this->user_1);
        $projectMember->setEmail('test3@example.org');
        $this->projectEmailInvitationRepo->add($projectMember);

        $projectMember = new \DSI\Entity\ProjectEmailInvitation();
        $projectMember->setProject($this->project_3);
        $projectMember->setByUser($this->user_1);
        $projectMember->setEmail('test4@example.org');
        $this->projectEmailInvitationRepo->add($projectMember);

        $this->assertCount(2, $this->projectEmailInvitationRepo->getByProjectID(
            $this->project_1->getId()
        ));
    }

    /** @test saveAsNew */
    public function cannotAddSameProjectEmailInvitationTwice()
    {
        $projectMember = new \DSI\Entity\ProjectEmailInvitation();
        $projectMember->setProject($this->project_1);
        $projectMember->setByUser($this->user_1);
        $projectMember->setEmail('test@example.org');
        $this->projectEmailInvitationRepo->add($projectMember);

        $projectMember = new \DSI\Entity\ProjectEmailInvitation();
        $projectMember->setProject($this->project_1);
        $projectMember->setByUser($this->user_1);
        $projectMember->setEmail('test@example.org');
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->projectEmailInvitationRepo->add($projectMember);
    }

    /** @test saveAsNew */
    public function getAllProjectsForEmail()
    {
        $email = 'test@example.org';

        $projectMember = new \DSI\Entity\ProjectEmailInvitation();
        $projectMember->setProject($this->project_1);
        $projectMember->setByUser($this->user_1);
        $projectMember->setEmail($email);
        $this->projectEmailInvitationRepo->add($projectMember);

        $projectMember = new \DSI\Entity\ProjectEmailInvitation();
        $projectMember->setProject($this->project_2);
        $projectMember->setByUser($this->user_1);
        $projectMember->setEmail($email);
        $this->projectEmailInvitationRepo->add($projectMember);

        $this->assertCount(2, $this->projectEmailInvitationRepo->getByEmail($email));
    }

    /** @test saveAsNew */
    public function canRemoveInvitation()
    {
        $email = 'test@example.org';

        $projectMember = new \DSI\Entity\ProjectEmailInvitation();
        $projectMember->setProject($this->project_1);
        $projectMember->setByUser($this->user_1);
        $projectMember->setEmail($email);
        $this->projectEmailInvitationRepo->add($projectMember);

        $this->assertCount(1, $this->projectEmailInvitationRepo->getByEmail($email));

        $projectMember = new \DSI\Entity\ProjectEmailInvitation();
        $projectMember->setProject($this->project_1);
        $projectMember->setByUser($this->user_1);
        $projectMember->setEmail($email);
        $this->projectEmailInvitationRepo->remove($projectMember);

        $this->assertCount(0, $this->projectEmailInvitationRepo->getByEmail($email));
    }

    /** @test saveAsNew */
    public function canCheckIfProjectInvitedEmail()
    {
        $projectMember = new \DSI\Entity\ProjectEmailInvitation();
        $projectMember->setProject($this->project_1);
        $projectMember->setByUser($this->user_1);
        $projectMember->setEmail($email1 = 'test@example.org');
        $this->projectEmailInvitationRepo->add($projectMember);

        $projectMember = new \DSI\Entity\ProjectEmailInvitation();
        $projectMember->setProject($this->project_2);
        $projectMember->setByUser($this->user_2);
        $projectMember->setEmail($email2 = 'test2@example.org');
        $this->projectEmailInvitationRepo->add($projectMember);

        $this->assertTrue($this->projectEmailInvitationRepo->projectInvitedEmail(
            $this->project_1->getId(), $email1)
        );
        $this->assertFalse($this->projectEmailInvitationRepo->projectInvitedEmail(
            $this->project_1->getId(), $email2)
        );
        $this->assertTrue($this->projectEmailInvitationRepo->projectInvitedEmail(
            $this->project_2->getId(), $email2)
        );
        $this->assertFalse($this->projectEmailInvitationRepo->projectInvitedEmail(
            $this->project_2->getId(), $email1)
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