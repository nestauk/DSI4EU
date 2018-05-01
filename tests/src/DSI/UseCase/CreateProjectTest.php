<?php

require_once __DIR__ . '/../../../config.php';

class CreateProjectTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\CreateProject */
    private $createProjectCommand;

    /** @var \DSI\Repository\ProjectMemberRepo */
    private $projectMemberRepo;

    /** @var \DSI\Repository\ProjectRepo */
    private $projectRepo;

    /** @var \DSI\Repository\UserRepo */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user;

    public function setUp()
    {
        $this->createProjectCommand = new \DSI\UseCase\CreateProject();
        $this->projectMemberRepo = new \DSI\Repository\ProjectMemberRepo();
        $this->projectRepo = new \DSI\Repository\ProjectRepo();
        $this->userRepo = new \DSI\Repository\UserRepo();

        $this->user = new \DSI\Entity\User();
        $this->userRepo->insert($this->user);
    }

    public function tearDown()
    {
        $this->projectRepo->clearAll();
        $this->userRepo->clearAll();
        $this->projectMemberRepo->clearAll();
        (new \DSI\Repository\ContentUpdateRepo())->clearAll();
    }

    /** @test */
    public function cannotCreateNewProjects()
    {
        $canCreateProjects = \DSI\Service\App::canCreateProjects();
        \DSI\Service\App::setCanCreateProjects(false);

        $this->createProjectCommand->data()->name = 'test';
        $this->createProjectCommand->data()->description = 'test';
        $this->createProjectCommand->data()->owner = $this->user;

        $e = null;
        try {
            $this->createProjectCommand->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('name'));

        \DSI\Service\App::setCanCreateProjects($canCreateProjects);
    }

    /** @ test */
    public function successfulCreation()
    {
        $this->createProjectCommand->data()->name = 'test';
        $this->createProjectCommand->data()->description = 'test';
        $this->createProjectCommand->data()->owner = $this->user;
        $this->createProjectCommand->exec();

        $this->assertCount(1, $this->projectRepo->getAll());

        $this->createProjectCommand->data()->name = 'test2';
        $this->createProjectCommand->data()->description = 'test';
        $this->createProjectCommand->data()->owner = $this->user;
        $this->createProjectCommand->exec();

        $this->assertCount(2, $this->projectRepo->getAll());
    }

    /** @ test */
    public function ownerIsAlsoMemberOfTheProject()
    {
        $this->createProjectCommand->data()->name = 'test';
        $this->createProjectCommand->data()->description = 'test';
        $this->createProjectCommand->data()->owner = $this->user;
        $this->createProjectCommand->exec();
        $project = $this->createProjectCommand->getProject();

        $this->assertTrue(
            $this->projectMemberRepo->projectHasMember($project, $this->user)
        );
    }

    /** @ test */
    public function ownerIsAdminOfTheProject()
    {
        $this->createProjectCommand->data()->name = 'test';
        $this->createProjectCommand->data()->description = 'test';
        $this->createProjectCommand->data()->owner = $this->user;
        $this->createProjectCommand->exec();
        $project = $this->createProjectCommand->getProject();

        $this->assertTrue(
            $this->projectMemberRepo->getByProjectAndMember(
                $project, $this->user
            )->isAdmin()
        );
    }

    /** @ test */
    public function projectIsWaitingApprovalOnCreation()
    {
        $this->createProjectCommand->data()->name = 'test';
        $this->createProjectCommand->data()->description = 'test';
        $this->createProjectCommand->data()->owner = $this->user;
        $this->createProjectCommand->exec();
        $project = $this->createProjectCommand->getProject();

        $project = (new \DSI\Repository\ProjectRepo())
            ->getById($project->getId());

        $this->assertTrue($project->isWaitingApproval());
    }

    /** @ test */
    public function cannotAddWithAnEmptyName()
    {
        $e = null;
        $this->createProjectCommand->data()->name = '';
        $this->createProjectCommand->data()->description = 'test';
        $this->createProjectCommand->data()->owner = $this->user;
        try {
            $this->createProjectCommand->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('name'));
    }
}