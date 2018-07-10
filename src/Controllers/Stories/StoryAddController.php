<?php

namespace Controllers\Stories;

use DSI\Entity\User;
use DSI\Repository\StoryCategoryRepo;
use DSI\Repository\UserRepo;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use Services\URL;
use DSI\UseCase\StoryAdd;
use Services\View;

class StoryAddController
{
    public function __construct()
    {
    }

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());
        $loggedInUser = $authUser->getUser();

        if (!$this->userCanAddStory($loggedInUser))
            go_to($urlHandler->home());

        if (isset($_POST['add'])) {
            try {
                $createStory = new StoryAdd();
                $createStory->data()->title = $_POST['title'] ?? '';
                $createStory->data()->cardShortDescription = $_POST['cardShortDescription'] ?? '';
                $createStory->data()->content = $_POST['content'] ?? '';
                if (isset($_POST['featuredImage']))
                    $createStory->data()->featuredImage = $_POST['featuredImage'];
                if (isset($_POST['mainImage']))
                    $createStory->data()->mainImage = $_POST['mainImage'];
                if (isset($_POST['datePublished']))
                    $createStory->data()->datePublished = $_POST['datePublished'];
                if (isset($_POST['isPublished']))
                    $createStory->data()->isPublished = (bool)$_POST['isPublished'];
                $createStory->data()->writerID = (int)$_POST['writerID'];
                $createStory->exec();

                $story = $createStory->getStory();

                echo json_encode([
                    'code' => 'ok',
                    'url' => $urlHandler->blogPost($story),
                ]);
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors(),
                ]);
            }
            die();
        }

        return View::render(__DIR__ . '/../../Views/stories/story-add.php', [
            'categories' => (new StoryCategoryRepo())->getAll(),
            'writers' => (new UserRepo())->getAll(),
            'loggedInUser' => $loggedInUser,
        ]);
    }

    /**
     * @param $loggedInUser
     * @return bool
     */
    private function userCanAddStory(User $loggedInUser): bool
    {
        $userCanAddStory = (bool)($loggedInUser AND ($loggedInUser->isCommunityAdmin() OR $loggedInUser->isEditorialAdmin()));
        return $userCanAddStory;
    }
}