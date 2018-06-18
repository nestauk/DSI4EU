<?php

namespace DSI\UseCase;

use DSI\Entity\Image;
use DSI\Entity\Story;
use DSI\NotEnoughData;
use DSI\Repository\StoryCategoryRepo;
use DSI\Repository\StoryRepo;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;

class StoryEdit
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var StoryRepo */
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
        $this->storyRepo = new StoryRepo();

        $this->assertDataHasBeenSent();
        $this->assertSentDataIsValid();

        $author = (new UserRepo())->getById($this->data->writerID);
        $story = $this->storyRepo->getById($this->data()->id);
        $story->setTitle((string)$this->data()->title);
        $story->setCardShortDescription((string)$this->data()->cardShortDescription);
        $story->setContent((string)$this->data()->content);
        $story->setAuthor($author);
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

        /*
        if (isset($this->data()->categoryID) AND $this->data()->categoryID <= 0) {
            $this->errorHandler->addTaggedError('category', 'Invalid category ID');
            throw $this->errorHandler;
        }
        */

        if (!$this->data()->title) {
            $this->errorHandler->addTaggedError('title', 'Please type a story title');
            throw $this->errorHandler;
        }
        if (!$this->data()->writerID) {
            $this->errorHandler->addTaggedError('writer', 'Please select an author');
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
        if (
            $this->data()->mainImage AND
            $this->data()->mainImage != Image::STORY_MAIN_IMAGE_URL . $story->getMainImage()
        ) {
            $updateMainImage = new UpdateStoryMainImage();
            $updateMainImage->data()->story = $story;
            $updateMainImage->data()->fileName = $this->data()->mainImage;
            $updateMainImage->exec();
        }
    }

    /**
     * @param $story
     */
    private function setFeaturedImage(Story $story)
    {
        if (
            $this->data()->featuredImage AND
            $this->data()->featuredImage != Image::STORY_FEATURED_IMAGE_URL . $story->getFeaturedImage()
        ) {
            $updateFeatImage = new UpdateStoryFeatImage();
            $updateFeatImage->data()->story = $story;
            $updateFeatImage->data()->fileName = $this->data()->featuredImage;
            $updateFeatImage->exec();
        }
    }
}

class StoryEdit_Data
{
    /** @var string */
    public $id,
        $title,
        $cardShortDescription,
        $content,
        $writerID,
        $featuredImage,
        $mainImage,
        $isPublished,
        $datePublished;
}