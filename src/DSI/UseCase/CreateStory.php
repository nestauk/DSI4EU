<?php

namespace DSI\UseCase;

use DSI\Entity\Story;
use DSI\Entity\User;
use DSI\NotEnoughData;
use DSI\Repository\StoryCategoryRepository;
use DSI\Repository\StoryRepository;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class CreateStory
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var StoryRepository */
    private $storyRepo;

    /** @var Story */
    private $story;

    /** @var CreateStory_Data */
    private $data;

    public function __construct()
    {
        $this->data = new CreateStory_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->storyRepo = new StoryRepository();

        if (!isset($this->data()->title))
            throw new NotEnoughData('title');
        if (!isset($this->data()->writerID))
            throw new NotEnoughData('writer');

        if ($this->data()->writerID <= 0) {
            $this->errorHandler->addTaggedError('writer', 'Invalid user ID');
            throw $this->errorHandler;
        }

        if (isset($this->data()->categoryID) AND $this->data()->categoryID <= 0) {
            $this->errorHandler->addTaggedError('category', 'Invalid category ID');
            throw $this->errorHandler;
        }

        if ($this->data()->title == '') {
            $this->errorHandler->addTaggedError('title', 'Please type a story title');
            throw $this->errorHandler;
        }

        $writer = (new UserRepository())->getById($this->data()->writerID);
        $category = (new StoryCategoryRepository())->getById($this->data()->categoryID);

        $story = new Story();
        if ($this->data()->categoryID)
            $story->setStoryCategory($category);
        $story->setTitle((string)$this->data()->title);
        $story->setContent((string)$this->data()->content);
        $story->setWriter($writer);
        $this->storyRepo->insert($story);

        $this->story = $this->storyRepo->getById($story->getId());
    }

    /**
     * @return CreateStory_Data
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
}

class CreateStory_Data
{
    /** @var string */
    public $title,
        $content,
        $writerID,
        $categoryID;
}