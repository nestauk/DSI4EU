<?php

require_once __DIR__ . '/../../../config.php';

class CreateProjectEmailInvitationTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\CreateProjectEmailInvitation */
    private $createProjectEmailInvitation;

    /** @var \DSI\Repository\ProjectEmailInvitationRepo */
    private $projectEmailInvitationRepo;

    /** @var \DSI\Repository\ProjectRepo */
    private $projectRepo;

    /** @var \DSI\Entity\Project */
    private $project;

    /** @var \DSI\Repository\UserRepo */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user_1, $user_2;

    public function setUp()
    {
        $this->createProjectEmailInvitation = new \DSI\UseCase\CreateProjectEmailInvitation();
        $this->projectEmailInvitationRepo = new \DSI\Repository\ProjectEmailInvitationRepo();
        $this->projectRepo = new \DSI\Repository\ProjectRepo();
        $this->userRepo = new \DSI\Repository\UserRepo();

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
        $this->projectEmailInvitationRepo->clearAll();
        $this->projectRepo->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test */
    public function successfulEmailInvitationToProject()
    {
        $email = 'test@example.org';
        $this->createProjectEmailInvitation->setByUser($this->user_2);
        $this->createProjectEmailInvitation->setProject($this->project);
        $this->createProjectEmailInvitation->setEmail($email);
        $this->createProjectEmailInvitation->exec();

        $this->assertTrue(
            $this->projectEmailInvitationRepo->projectInvitedEmail(
                $this->project->getId(),
                $email
            )
        );
    }

    /** @test */
    public function cannotInviteSameEmailTwice()
    {
        $e = null;
        $email = 'test@example.org';
        $this->createProjectEmailInvitation->setByUser($this->user_2);
        $this->createProjectEmailInvitation->setProject($this->project);
        $this->createProjectEmailInvitation->setEmail($email);
        $this->createProjectEmailInvitation->exec();

        try {
            $this->createProjectEmailInvitation->setByUser($this->user_2);
            $this->createProjectEmailInvitation->setProject($this->project);
            $this->createProjectEmailInvitation->setEmail($email);
            $this->createProjectEmailInvitation->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('email'));
    }
}