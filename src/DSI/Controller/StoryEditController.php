<?php

namespace DSI\Controller;

use DSI\Entity\Image;
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
        $userCanEditStory = (bool)($loggedInUser AND ($loggedInUser->isCommunityAdmin() OR $loggedInUser->isEditorialAdmin()));
        if (!$userCanEditStory)
            go_to(URL::home());

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
                'featuredImage' => Image::STORY_FEATURED_IMAGE_URL . $story->getFeaturedImage(),
                'mainImage' => Image::STORY_MAIN_IMAGE_URL . $story->getMainImage(),
                'isPublished' => $story->isPublished(),
                'categoryID' => $story->getStoryCategoryId(),
            ]);
            die();
        }

        $categories = (new StoryCategoryRepository())->getAll();

        $angularModules['fileUpload'] = true;
        require __DIR__ . '/../../../www/story-edit.php';
    }
}