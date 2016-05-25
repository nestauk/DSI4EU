<?php

require_once __DIR__ . '/../../../config.php';

class CreateProjectEmailInvitationTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\CreateProjectEmailInvitation */
    private $createProjectEmailInvitation;

    /** @var \DSI\Repository\ProjectEmailInvitationRepository */
    private $projectEmailInvitationRepo;

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
        $this->createProjectEmailInvitation = new \DSI\UseCase\CreateProjectEmailInvitation();
        $this->projectEmailInvitationRepo = new \DSI\Repository\ProjectEmailInvitationRepository();
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
        $this->projectEmailInvitationRepo->clearAll();
        $this->projectRepo->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test */
    public function successfulEmailInvitationToProject()
    {
        $email = 'test@example.org';
        $this->createProjectEmailInvitation->data()->byUserID = $this->user_2->getId();
        $this->createProjectEmailInvitation->data()->projectID = $this->project->getId();
        $this->createProjectEmailInvitation->data()->email = $email;
        $this->createProjectEmailInvitation->exec();

        $this->assertTrue(
            $this->projectEmailInvitationRepo->projectInvitedEmail(
                $this->createProjectEmailInvitation->data()->projectID,
                $email
            )
        );
    }

    /** @test */
    public function cannotInviteSameEmailTwice()
    {
        $e = null;
        $email = 'test@example.org';
        $this->createProjectEmailInvitation->data()->byUserID = $this->user_2->getId();
        $this->createProjectEmailInvitation->data()->projectID = $this->project->getId();
        $this->createProjectEmailInvitation->data()->email = $email;
        $this->createProjectEmailInvitation->exec();

        try {
            $this->createProjectEmailInvitation->data()->byUserID = $this->user_2->getId();
            $this->createProjectEmailInvitation->data()->projectID = $this->project->getId();
            $this->createProjectEmailInvitation->data()->email = $email;
            $this->createProjectEmailInvitation->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('email'));
    }
}