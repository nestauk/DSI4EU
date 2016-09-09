<?php

namespace DSI\Controller;

use DSI\Repository\StoryRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;

class StoryController
{
    /** @var  StoryController_Data */
    private $data;

    public function __construct()
    {
        $this->data = new StoryController_Data();
    }

    public function exec()
    {
        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();

        $storyRepo = new StoryRepository();
        $story = $storyRepo->getById($this->data()->storyID);
        $author = $story->getAuthor();
        $stories = $storyRepo->getPublishedLast(5);

        $pageTitle = $story->getTitle();
        require __DIR__ . '/../../../www/views/story.php';
    }

    /**
     * @return StoryController_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class StoryController_Data
{
    /** @var  int */
    public $storyID;

    /** @var string */
    public $format = 'html';
}