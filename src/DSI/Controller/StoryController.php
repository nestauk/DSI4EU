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
        if ($authUser->isLoggedIn())
            $loggedInUser = (new UserRepository())->getById($authUser->getUserId());

        $storyRepo = new StoryRepository();
        $story = $storyRepo->getById($this->data()->storyID);
        $author = $story->getAuthor();
        $stories = $storyRepo->getPublishedLast(5);

        $pageTitle = $story->getTitle();
        require __DIR__ . '/../../../www/story.php';
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