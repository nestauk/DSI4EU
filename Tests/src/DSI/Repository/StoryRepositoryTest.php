<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository\UserRepository;
use \DSI\Repository\StoryRepository;
use \DSI\Repository\StoryCategoryRepository;
use \DSI\Entity\Story;
use \DSI\Entity\User;

class StoryRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var StoryCategoryRepository */
    private $storyCategoryRepository;

    /** @var StoryRepository */
    private $storyRepository;

    /** @var UserRepository */
    private $userRepo;

    /** @var User */
    private $user1, $user2;

    public function setUp()
    {
        $this->storyCategoryRepository = new StoryCategoryRepository();
        $this->storyRepository = new StoryRepository();
        $this->userRepo = new UserRepository();

        $this->user1 = new User();
        $this->user2 = new User();
        $this->userRepo->insert($this->user1);
        $this->userRepo->insert($this->user2);
    }

    public function tearDown()
    {
        $this->storyCategoryRepository->clearAll();
        $this->storyRepository->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test saveAsNew */
    public function canBeAdded()
    {
        $story = new Story();
        $story->setAuthor($this->user1);
        $this->storyRepository->insert($story);

        $this->assertEquals(1, $story->getId());
    }

    /** @test save, getByID */
    public function canBeUpdated()
    {
        $story = new Story();
        $story->setAuthor($this->user1);
        $this->storyRepository->insert($story);

        $story->setAuthor($this->user2);
        $this->storyRepository->save($story);

        $sameStory = $this->storyRepository->getById($story->getId());
        $this->assertEquals($this->user2->getId(), $sameStory->getAuthor()->getId());
    }

    /** @test getByID */
    public function gettingAnNonExistentStoryById_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->storyRepository->getById(1);
    }

    /** @test save */
    public function NonexistentStoryCannotBeSaved()
    {
        $story = new Story();
        $story->setId(1);
        $story->setAuthor($this->user1);

        $this->setExpectedException(\DSI\NotFound::class);
        $this->storyRepository->save($story);
    }

    /** @test getAll */
    public function getAllStories()
    {
        $story = new Story();
        $story->setAuthor($this->user1);
        $this->storyRepository->insert($story);

        $this->assertCount(1, $this->storyRepository->getAll());

        $story = new Story();
        $story->setAuthor($this->user1);
        $this->storyRepository->insert($story);

        $this->assertCount(2, $this->storyRepository->getAll());
    }

    /** @test */
    public function searchByTitle()
    {
        $this->addStory('title 1');
        $this->addStory('title 2');
        $this->addStory('title 3');
        $this->addStory('different 1');

        $this->assertCount(0, $this->storyRepository->searchByTitle('4'));
        $this->assertCount(1, $this->storyRepository->searchByTitle('2'));
        $this->assertCount(2, $this->storyRepository->searchByTitle('1'));
        $this->assertCount(3, $this->storyRepository->searchByTitle('title'));
        $this->assertCount(3, $this->storyRepository->searchByTitle(' ', 3));
        $this->assertCount(4, $this->storyRepository->searchByTitle(' '));
    }

    /** @test */
    public function setAllStoryDetails()
    {
        $category = new \DSI\Entity\StoryCategory();
        $this->storyCategoryRepository->insert($category);

        $story = new Story();
        $story->setStoryCategory($category);
        $story->setAuthor($this->user1);
        $story->setTitle($title = 'Name');
        $story->setContent($content = 'Desc');
        $story->setFeaturedImage($featuredImage = 'DSC.jpg');
        $story->setMainImage($bgImage = 'DSC.jpg');
        $story->setIsPublished(true);
        $story->setDatePublished($datePublished = '2016-03-03');
        $this->storyRepository->insert($story);

        $story = $this->storyRepository->getById($story->getId());
        $this->assertEquals($category->getId(), $story->getStoryCategoryId());
        $this->assertEquals($this->user1->getId(), $story->getAuthor()->getId());
        $this->assertEquals($title, $story->getTitle());
        $this->assertEquals($content, $story->getContent());
        $this->assertEquals($featuredImage, $story->getFeaturedImage());
        $this->assertEquals($bgImage, $story->getMainImage());
        $this->assertTrue($story->isPublished());
        $this->assertEquals($datePublished, $story->getDatePublished());
        $this->assertRegExp('<^\d{4}\-\d{2}\-\d{2} \d{2}\:\d{2}\:\d{2}$>', $story->getTime());
    }


    private function addStory($title)
    {
        $story = new Story();
        $story->setTitle($title);
        $story->setAuthor($this->user1);
        $this->storyRepository->insert($story);
        return $story;
    }
}