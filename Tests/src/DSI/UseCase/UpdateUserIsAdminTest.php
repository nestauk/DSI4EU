<?php

require_once __DIR__ . '/../../../config.php';

use DSI\Service\ErrorHandler;
use DSI\Repository\UserRepository;

class UpdateUserIsAdminTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\UpdateUserIsAdmin */
    private $updateUserIsAdminCommand;

    /** @var UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $superAdmin,
        $normalUser,
        $user;

    public function setUp()
    {
        $this->updateUserIsAdminCommand = new \DSI\UseCase\UpdateUserIsAdmin();

        $this->userRepo = new UserRepository();

        $this->superAdmin = new \DSI\Entity\User();
        $this->superAdmin->setIsSuperAdmin(true);
        $this->userRepo->insert($this->superAdmin);

        $this->normalUser = new \DSI\Entity\User();
        $this->userRepo->insert($this->normalUser);

        $this->user = new \DSI\Entity\User();
        $this->userRepo->insert($this->user);
    }

    public function tearDown()
    {
        $this->userRepo->clearAll();
    }

    /** @test */
    public function successfulSetUserAsAdmin()
    {
        try {
            $this->updateUserIsAdminCommand->data()->userID = $this->user->getId();
            $this->updateUserIsAdminCommand->data()->executor = $this->superAdmin;
            $this->updateUserIsAdminCommand->data()->isAdmin = true;
            $this->updateUserIsAdminCommand->exec();
        } catch (ErrorHandler $e) {
            $this->assertNull($e);
        }

        $user = $this->userRepo->getById($this->user->getId());
        $this->assertTrue($user->isAdmin());
    }

    /** @test */
    public function onlySuperAdminCanMakeChanges()
    {
        $e = null;
        try {
            $this->updateUserIsAdminCommand->data()->userID = $this->user->getId();
            $this->updateUserIsAdminCommand->data()->executor = $this->normalUser;
            $this->updateUserIsAdminCommand->data()->isAdmin = true;
            $this->updateUserIsAdminCommand->exec();
        } catch (ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('executor'));
    }
}