<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository\StoryCategoryRepository;
use \DSI\Entity\StoryCategory;

class StoryCategoryRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var StoryCategoryRepository */
    private $storyCategoryRepository;

    public function setUp()
    {
        $this->storyCategoryRepository = new StoryCategoryRepository();
    }

    public function tearDown()
    {
        $this->storyCategoryRepository->clearAll();
    }

    /** @test saveAsNew */
    public function storyCategoryCanBeSaved()
    {
        $story = new StoryCategory();
        $this->storyCategoryRepository->insert($story);

        $this->assertEquals(1, $story->getId());
    }

    /** @test save, getByID */
    public function canBeUpdated()
    {
        $storyCategory = new StoryCategory();
        $storyCategory->setName('test');
        $this->storyCategoryRepository->insert($storyCategory);

        $storyCategory->setName($name = 'test 2');
        $this->storyCategoryRepository->save($storyCategory);

        $sameStory = $this->storyCategoryRepository->getById($storyCategory->getId());
        $this->assertEquals($name, $sameStory->getName());
    }

    /** @test getByID */
    public function gettingAnNonExistentStoryById_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->storyCategoryRepository->getById(1);
    }

    /** @test save */
    public function NonexistentStoryCannotBeSaved()
    {
        $storyCategory = new StoryCategory();
        $storyCategory->setId(1);
        $storyCategory->setName('name');

        $this->setExpectedException(\DSI\NotFound::class);
        $this->storyCategoryRepository->save($storyCategory);
    }

    /** @test getAll */
    public function getAll()
    {
        $storyCategory = new StoryCategory();
        $storyCategory->setName('test');
        $this->storyCategoryRepository->insert($storyCategory);

        $this->assertCount(1, $this->storyCategoryRepository->getAll());

        $storyCategory = new StoryCategory();
        $storyCategory->setName('test 2');
        $this->storyCategoryRepository->insert($storyCategory);

        $this->assertCount(2, $this->storyCategoryRepository->getAll());
    }

    /** @test */
    public function setAllDetails()
    {
        $storyCategory = new StoryCategory();
        $storyCategory->setName($name = 'name');
        $this->storyCategoryRepository->insert($storyCategory);

        $storyCategory = $this->storyCategoryRepository->getById($storyCategory->getId());
        $this->assertEquals($name, $storyCategory->getName());
    }
}