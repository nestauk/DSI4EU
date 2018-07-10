<?php

namespace Controllers\Stories;

use DSI\Entity\Image;
use DSI\Repository\StoryCategoryRepo;
use DSI\Repository\StoryRepo;
use DSI\Repository\UserRepo;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use Services\URL;
use DSI\UseCase\StoryEdit;
use Services\View;

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
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());
        $loggedInUser = $authUser->getUser();

        $userCanEditStory = (bool)($loggedInUser AND ($loggedInUser->isCommunityAdmin() OR $loggedInUser->isEditorialAdmin()));
        if (!$userCanEditStory)
            go_to($urlHandler->home());

        $storyRepo = new StoryRepo();
        $story = $storyRepo->getById($this->storyID);

        if ($this->format == 'json') {
            if (isset($_POST['save'])) {
                try {
                    $editStory = new StoryEdit();
                    $editStory->data()->id = $story->getId();
                    $editStory->data()->title = $_POST['title'] ?? '';
                    $editStory->data()->cardShortDescription = $_POST['cardShortDescription'] ?? '';
                    $editStory->data()->content = $_POST['content'] ?? '';
                    if (isset($_POST['featuredImage']))
                        $editStory->data()->featuredImage = $_POST['featuredImage'];
                    if (isset($_POST['mainImage']))
                        $editStory->data()->mainImage = $_POST['mainImage'];
                    $editStory->data()->datePublished = $_POST['datePublished'] ?? '';
                    $editStory->data()->isPublished = (bool)$_POST['isPublished'] ?? false;
                    $editStory->data()->writerID = (int)$_POST['writerID'];

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
                'cardShortDescription' => $story->getCardShortDescription(),
                'datePublished' => $story->getDatePublished(),
                'featuredImage' => Image::STORY_FEATURED_IMAGE_URL . $story->getFeaturedImage(),
                'mainImage' => Image::STORY_MAIN_IMAGE_URL . $story->getMainImage(),
                'isPublished' => $story->isPublished(),
                'writerID' => (string)$story->getAuthor()->getId(),
            ]);
            die();
        }

        View::render(__DIR__ . '/../../Views/stories/story-edit.php', [
            'categories' => (new StoryCategoryRepo())->getAll(),
            'writers' => (new UserRepo())->getAll(),
            'story' => $story,
            'loggedInUser' => $loggedInUser,
        ]);
    }
}