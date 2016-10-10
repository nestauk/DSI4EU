<?php

namespace DSI\Controller;

use DSI\Entity\Story;
use DSI\Entity\User;
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

        $userCanManageStory = $this->userCanManageStory($loggedInUser, $story);

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

    /**
     * @param User $loggedInUser
     * @param Story $story
     * @return bool
     */
    private function userCanManageStory($loggedInUser, Story $story)
    {
        if (!$loggedInUser)
            return false;

        $author = $story->getAuthor();
        if ($loggedInUser->getId() == $author->getId())
            return true;

        if ($loggedInUser->isCommunityAdmin() OR $loggedInUser->isEditorialAdmin())
            return true;

        return false;
    }
}

class StoryController_Data
{
    /** @var  int */
    public $storyID;

    /** @var string */
    public $format = 'html';
}