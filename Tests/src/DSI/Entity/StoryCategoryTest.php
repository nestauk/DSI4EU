<?php

use DSI\Entity\StoryCategory;

require_once __DIR__ . '/../../../config.php';

class StoryCategoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var StoryCategory */
    private $storyCategory;

    public function setUp()
    {
        $this->storyCategory = new StoryCategory();
    }

    /** @test setId, getId */
    public function settingAnId_returnsTheId()
    {
        $this->storyCategory->setId(1);
        $this->assertEquals(1, $this->storyCategory->getId());
    }

    /** @test setId */
    public function settingAnInvalidId_throwsException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->storyCategory->setId(0);
    }

    /** @test */
    public function settingTitle_returnsTitle()
    {
        $categoryName = 'Brand New Category';
        $this->storyCategory->setName($categoryName);
        $this->assertEquals($categoryName, $this->storyCategory->getName());
    }
}