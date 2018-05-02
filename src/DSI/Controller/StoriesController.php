<?php

namespace DSI\Controller;

use DSI\Entity\Story;
use DSI\Repository\StoryRepo;
use DSI\Repository\UserRepo;
use DSI\Service\Auth;
use Services\URL;

class StoriesController
{
    public $format = 'html';

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();
        $userCanAddStory = (bool)($loggedInUser AND ($loggedInUser->isCommunityAdmin() OR $loggedInUser->isEditorialAdmin()));

        if ($this->format == 'json') {
            if ($userCanAddStory)
                $stories = (new StoryRepo())->getAll();
            else
                $stories = (new StoryRepo())->getAllPublished();

            echo json_encode(array_map(function (Story $story) use ($urlHandler) {
                $data = [
                    'id' => $story->getId(),
                    'title' => $story->getTitle(),
                    'featuredImage' => $story->getFeaturedImage(),
                    'content' => $story->getCardShortDescription(),
                    // 'content' => html_entity_decode(substr(strip_tags($story->getContent()), 0, 150)),
                    'categoryID' => $story->getStoryCategoryId(),
                    'datePublished' => $story->getDatePublished('jS F Y'),
                    'url' => $urlHandler->blogPost($story),
                    'editUrl' => $urlHandler->blogPostEdit($story),
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
            require __DIR__ . '/../../../www/views/stories.php';
        }
    }
}