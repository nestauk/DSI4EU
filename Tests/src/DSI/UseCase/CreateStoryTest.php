<?php

require_once __DIR__ . '/../../../config.php';

class CreateStoryTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\StoryAdd */
    private $createStoryCmd;

    /** @var \DSI\Repository\StoryCategoryRepository */
    private $storyCategoryRepo;

    /** @var \DSI\Repository\StoryRepository */
    private $storyRepo;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user;

    public function setUp()
    {
        $this->createStoryCmd = new \DSI\UseCase\StoryAdd();

        $this->storyCategoryRepo = new \DSI\Repository\StoryCategoryRepository();
        $this->storyRepo = new \DSI\Repository\StoryRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();

        $this->user = new \DSI\Entity\User();
        $this->userRepo->insert($this->user);
    }

    public function tearDown()
    {
        $this->storyCategoryRepo->clearAll();
        $this->storyRepo->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test */
    public function successfulCreation()
    {
        $category = new \DSI\Entity\StoryCategory();
        $this->storyCategoryRepo->insert($category);

        $this->createStoryCmd->data()->title = 'test';
        $this->createStoryCmd->data()->cardShortDescription = 'card short desc';
        $this->createStoryCmd->data()->content = 'content';
        $this->createStoryCmd->data()->authorID = $this->user->getId();
        $this->createStoryCmd->exec();
        $this->assertCount(1, $this->storyRepo->getAll());

        $this->createStoryCmd->data()->title = 'test';
        $this->createStoryCmd->data()->cardShortDescription = 'card short desc';
        $this->createStoryCmd->data()->content = 'content';
        $this->createStoryCmd->data()->authorID = $this->user->getId();
        $this->createStoryCmd->exec();
        $this->assertCount(2, $this->storyRepo->getAll());
    }

    /** @test */
    public function cannotAddWithAnEmptyTitle()
    {
        $e = null;
        $this->createStoryCmd->data()->title = '';
        $this->createStoryCmd->data()->content = 'content';
        $this->createStoryCmd->data()->cardShortDescription = 'card short description';
        $this->createStoryCmd->data()->authorID = $this->user->getId();
        try {
            $this->createStoryCmd->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('title'));
    }
}