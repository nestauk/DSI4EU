<?php

namespace DSI\Controller;

use DSI\Repository\StoryCategoryRepository;
use DSI\Repository\StoryRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\StoryEdit;

class StoryEditController
{
    /** @var int */
    public $storyID;
    /** @var string */
    public $format = 'html';

    public function __construct()
    {
    }

    public function exec()
    {
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo(URL::login());

        $loggedInUser = (new UserRepository())->getById($authUser->getUserId());

        $storyRepo = new StoryRepository();
        $story = $storyRepo->getById($this->storyID);

        if ($this->format == 'json') {
            if (isset($_POST['save'])) {
                try {
                    $editStory = new StoryEdit();
                    $editStory->data()->id = $story->getId();
                    $editStory->data()->title = $_POST['title'] ?? '';
                    if (isset($_POST['categoryID']))
                        $editStory->data()->categoryID = $_POST['categoryID'];
                    if (isset($_POST['content']))
                        $editStory->data()->content = $_POST['content'];
                    if (isset($_POST['featuredImage']))
                        $editStory->data()->featuredImage = $_POST['featuredImage'];
                    if (isset($_POST['mainImage']))
                        $editStory->data()->mainImage = $_POST['mainImage'];
                    if (isset($_POST['datePublished']))
                        $editStory->data()->datePublished = $_POST['datePublished'];
                    if (isset($_POST['isPublished']))
                        $editStory->data()->isPublished = (bool)$_POST['isPublished'];

                    $editStory->exec();

                    echo json_encode([
                        'code' => 'ok',
                    ]);
                } catch (ErrorHandler $e) {
                    echo json_encode([
                        'code' => 'error',
                        'errors' => $e->getErrors(),
                    ]);
                }
                die();
            }

            echo json_encode([
                'title' => $story->getTitle(),
                'datePublished' => $story->getDatePublished(),
                'featuredImage' => $story->getFeaturedImage(),
                'mainImage' => $story->getMainImage(),
                'isPublished' => $story->isPublished(),
                'categoryID' => $story->getStoryCategoryId(),
            ]);
            die();
        }

        $categories = (new StoryCategoryRepository())->getAll();

        require __DIR__ . '/../../../www/story-edit.php';
    }
}