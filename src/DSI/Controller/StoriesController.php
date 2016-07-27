<?php

namespace DSI\Controller;

use DSI\Entity\Story;
use DSI\Repository\StoryRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class StoriesController
{
    public $format = 'html';

    public function exec()
    {
        $authUser = new Auth();
        if ($authUser->isLoggedIn())
            $loggedInUser = (new UserRepository())->getById($authUser->getUserId());
        else
            $loggedInUser = null;

        if ($this->format == 'json') {
            if ($loggedInUser)
                $stories = (new StoryRepository())->getAll();
            else
                $stories = (new StoryRepository())->getAllPublished();
            
            echo json_encode(array_map(function (Story $story) {
                $data = [
                    'id' => $story->getId(),
                    'title' => $story->getTitle(),
                    'featuredImage' => $story->getFeaturedImage(),
                    'content' => substr(strip_tags($story->getContent()), 0, 150),
                    'categoryID' => $story->getStoryCategoryId(),
                    'datePublished' => $story->getDatePublished('jS F Y'),
                    'url' => URL::story($story),
                    'editUrl' => URL::storyEdit($story->getId()),
                    'isPublished' => $story->isPublished(),
                ];
                if ($category = $story->getStoryCategory())
                    $data['category'] = $story->getStoryCategory()->getName();
                return $data;
            }, $stories));
        } else {
            $angularModules['animate'] = true;
            $angularModules['pagination'] = true;
            $pageTitle = 'Stories';
            require __DIR__ . '/../../../www/stories.php';
        }
    }
}