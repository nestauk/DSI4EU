<?php

namespace DSI\UseCase;

use DSI\Entity\Story;
use DSI\NotEnoughData;
use DSI\Repository\StoryCategoryRepository;
use DSI\Repository\StoryRepository;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class StoryEdit
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var StoryRepository */
    private $storyRepo;

    /** @var Story */
    private $story;

    /** @var StoryEdit_Data */
    private $data;

    public function __construct()
    {
        $this->data = new StoryEdit_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->storyRepo = new StoryRepository();

        $this->assertDataHasBeenSent();
        $this->assertSentDataIsValid();

        $story = $this->storyRepo->getById($this->data()->id);
        $story->setTitle((string)$this->data()->title);
        $story->setContent((string)$this->data()->content);
        $this->setCategory($story);
        // $this->setAuthor($story);
        $this->setFeaturedImage($story);
        $this->setMainImage($story);
        $this->setDatePublished($story);
        $story->setIsPublished($this->data()->isPublished);

        $this->storyRepo->save($story);
        $this->story = $this->storyRepo->getById($story->getId());
    }

    /**
     * @return StoryEdit_Data
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return Story
     */
    public function getStory()
    {
        return $this->story;
    }

    private function assertDataHasBeenSent()
    {
        if (!isset($this->data()->title))
            throw new NotEnoughData('title');
        //if (!isset($this->data()->authorID))
          //  throw new NotEnoughData('author');
    }

    private function assertSentDataIsValid()
    {
        /*
        if ($this->data()->authorID <= 0) {
            $this->errorHandler->addTaggedError('author', 'Invalid user ID');
            throw $this->errorHandler;
        }
        */

        if (isset($this->data()->categoryID) AND $this->data()->categoryID <= 0) {
            $this->errorHandler->addTaggedError('category', 'Invalid category ID');
            throw $this->errorHandler;
        }

        if ($this->data()->title == '') {
            $this->errorHandler->addTaggedError('title', 'Please type a story title');
            throw $this->errorHandler;
        }
    }

    /**
     * @param $story
     */
    private function setDatePublished(Story $story)
    {
        if ($this->data()->datePublished)
            $story->setDatePublished($this->data()->datePublished);
        else
            $story->setDatePublished(date('Y-m-d'));
    }

    /**
     * @param $story
     */
    private function setMainImage(Story $story)
    {
        if ($this->data()->mainImage) {
            copy(
                __DIR__ . '/../../../www/images/tmp/' . $this->data()->mainImage,
                __DIR__ . '/../../../www/images/stories/main/' . $this->data()->mainImage
            );
            $story->setMainImage($this->data()->mainImage);
        }
    }

    /**
     * @param $story
     */
    private function setFeaturedImage(Story $story)
    {
        if ($this->data()->featuredImage) {
            copy(
                __DIR__ . '/../../../www/images/tmp/' . $this->data()->featuredImage,
                __DIR__ . '/../../../www/images/stories/feat/' . $this->data()->featuredImage
            );
            $story->setFeaturedImage($this->data()->featuredImage);
        }
    }

    /**
     * @param $story
     */
    private function setAuthor(Story $story)
    {
        $author = (new UserRepository())->getById($this->data()->authorID);
        $story->setAuthor($author);
    }

    /**
     * @param $story
     */
    private function setCategory(Story $story)
    {
        if ($this->data()->categoryID) {
            $category = (new StoryCategoryRepository())->getById($this->data()->categoryID);
            $story->setStoryCategory($category);
        }
    }
}

class StoryEdit_Data
{
    /** @var string */
    public $id,
        $title,
        $content,
        $authorID,
        $categoryID,
        $featuredImage,
        $mainImage,
        $isPublished,
        $datePublished;
}