<?php

namespace DSI\Controller;

use DSI\Entity\Story;
use DSI\Entity\User;
use DSI\Repository\StoryRepo;
use DSI\Repository\UserRepo;
use DSI\Service\Auth;
use Services\URL;

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
        $urlHandler = new URL();
        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();

        $storyRepo = new StoryRepo();
        $story = $storyRepo->getById($this->data()->storyID);
        $author = $story->getAuthor();
        $stories = $storyRepo->getPublishedLast(5);

        $userCanManageStory = $this->userCanManageStory($loggedInUser, $story);
        $userCanDeleteStory = $this->userCanDeleteStory($loggedInUser, $story);

        \Services\View::setPageTitle($story->getTitle());
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

    /**
     * @param User $loggedInUser
     * @param Story $story
     * @return bool
     */
    private function userCanDeleteStory($loggedInUser, Story $story)
    {
        if (!$loggedInUser)
            return false;

        if ($loggedInUser->isSysAdmin())
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