<?php

use DSI\Entity\Story;

require_once __DIR__ . '/../../../config.php';

class StoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var Story */
    private $story;

    /** @var \DSI\Entity\StoryCategory */
    private $storyCategory;

    /** @var \DSI\Entity\User */
    private $writer;

    public function setUp()
    {
        $this->story = new Story();
        $this->writer = new \DSI\Entity\User();
        $this->writer->setId(1);
    }

    /** @test setId, getId */
    public function settingAnId_returnsTheId()
    {
        $this->story->setId(1);
        $this->assertEquals(1, $this->story->getId());
    }

    /** @test setId */
    public function settingAnInvalidId_throwsException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->story->setId(0);
    }

    /** @test */
    public function settingTitle_returnsTitle()
    {
        $storyTitle = 'Brand New Story';
        $this->story->setTitle($storyTitle);
        $this->assertEquals($storyTitle, $this->story->getTitle());
    }

    /** @test  */
    public function settingContent_returnsContent()
    {
        $storyContent = 'Brand New Project Desc';
        $this->story->setContent($storyContent);
        $this->assertEquals($storyContent, $this->story->getContent());
    }

    /** @test  */
    public function settingTime_returnsTime()
    {
        $storyTime = '2016-10-10 10:10:10';
        $this->story->setTime($storyTime);
        $this->assertEquals($storyTime, $this->story->getTime());
    }

    /** @test  */
    public function settingBgImage_returnsBgImage()
    {
        $bgImage = 'DSC.jpg';
        $this->story->setBgImage($bgImage);
        $this->assertEquals($bgImage, $this->story->getBgImage());
    }

    /** @test  */
    public function settingCategory_returnsCategory()
    {
        $this->storyCategory = new \DSI\Entity\StoryCategory();
        $this->storyCategory->setId(12);
        $this->story->setStoryCategory($this->storyCategory);
        $this->assertEquals($this->storyCategory->getId(), $this->story->getStoryCategory()->getId());
        $this->assertEquals($this->storyCategory->getId(), $this->story->getStoryCategoryId());
    }
}