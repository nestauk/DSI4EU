<?php

require_once __DIR__ . '/../../../config.php';

class CreateProjectPostTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\CreateProjectPost */
    private $createPostCmd;

    /** @var \DSI\Repository\ProjectPostRepository */
    private $projectPostRepo;

    /** @var \DSI\Repository\ProjectRepository */
    private $projectRepo;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\Project */
    private $project;

    /** @var \DSI\Entity\User */
    private $owner, $otherUser, $admin;

    public function setUp()
    {
        $this->createPostCmd = new \DSI\UseCase\CreateProjectPost();
        $this->projectPostRepo = new \DSI\Repository\ProjectPostRepository();
        $this->projectRepo = new \DSI\Repository\ProjectRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();

        $this->owner = new \DSI\Entity\User();
        $this->userRepo->insert($this->owner);
        $this->admin = new \DSI\Entity\User();
        $this->userRepo->insert($this->admin);
        $this->otherUser = new \DSI\Entity\User();
        $this->userRepo->insert($this->otherUser);

        $this->project = new \DSI\Entity\Project();
        $this->project->setOwner($this->owner);
        $this->projectRepo->insert($this->project);

        $this->makeAdmin($this->admin);
    }

    public function tearDown()
    {
        $this->projectPostRepo->clearAll();
        $this->projectRepo->clearAll();
        $this->userRepo->clearAll();
        (new \DSI\Repository\ProjectMemberRepository())->clearAll();
    }

    /** @test */
    public function theOwnerCanAddAPost()
    {
        $e = null;
        $this->createPostCmd->data()->project = $this->project;
        $this->createPostCmd->data()->executor = $this->owner;
        $this->createPostCmd->data()->title = 'Post Title';
        $this->createPostCmd->data()->text = 'Post Text';
        try {
            $this->createPostCmd->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNull($e);
    }

    /** @test */
    public function adminsCanAddPosts()
    {
        $e = null;
        $this->createPostCmd->data()->project = $this->project;
        $this->createPostCmd->data()->executor = $this->admin;
        $this->createPostCmd->data()->title = 'Post Title';
        $this->createPostCmd->data()->text = 'Post Text';
        try {
            $this->createPostCmd->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNull($e);
    }

    /** @test */
    public function otherUsersCannotAddPost()
    {
        $e = null;
        $this->createPostCmd->data()->project = $this->project;
        $this->createPostCmd->data()->executor = $this->otherUser;
        $this->createPostCmd->data()->title = 'Post Title';
        $this->createPostCmd->data()->text = 'Post Text';
        try {
            $this->createPostCmd->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('user'));
    }

    /** @test */
    public function successfulCreation()
    {
        $this->createPostCmd->data()->project = $this->project;
        $this->createPostCmd->data()->executor = $this->owner;
        $this->createPostCmd->data()->title = 'Post Title';
        $this->createPostCmd->data()->text = 'Post Text';
        $this->createPostCmd->exec();

        $this->assertCount(1, $this->projectPostRepo->getAll());

        $this->createPostCmd->data()->project = $this->project;
        $this->createPostCmd->data()->executor = $this->owner;
        $this->createPostCmd->data()->title = 'Post Title';
        $this->createPostCmd->data()->text = 'Post Text';
        $this->createPostCmd->exec();

        $this->assertCount(2, $this->projectPostRepo->getAll());
    }

    private function makeAdmin(\DSI\Entity\User $admin)
    {
        $addMember = new \DSI\UseCase\AddMemberToProject();
        $addMember->data()->projectID = $this->project->getId();
        $addMember->data()->userID = $admin->getId();
        $addMember->exec();

        $setAdmin = new \DSI\UseCase\SetAdminStatusToProjectMember();
        $setAdmin->data()->executor = $this->owner;
        $setAdmin->data()->project = $this->project;
        $setAdmin->data()->member = $admin;
        $setAdmin->data()->isAdmin = true;
        $setAdmin->exec();
    }
}