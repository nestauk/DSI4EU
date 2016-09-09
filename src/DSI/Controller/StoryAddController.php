<?php

namespace DSI\Controller;

use DSI\Entity\User;
use DSI\Repository\StoryCategoryRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\StoryAdd;

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
                $createStory->data()->authorID = $loggedInUser->getId();
                $createStory->data()->title = $_POST['title'] ?? '';
                if (isset($_POST['categoryID']))
                    $createStory->data()->categoryID = $_POST['categoryID'];
                if (isset($_POST['content']))
                    $createStory->data()->content = $_POST['content'];
                if (isset($_POST['featuredImage']))
                    $createStory->data()->featuredImage = $_POST['featuredImage'];
                if (isset($_POST['mainImage']))
                    $createStory->data()->mainImage = $_POST['mainImage'];
                if (isset($_POST['datePublished']))
                    $createStory->data()->datePublished = $_POST['datePublished'];
                if (isset($_POST['isPublished']))
                    $createStory->data()->isPublished = (bool)$_POST['isPublished'];

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

        $categories = (new StoryCategoryRepository())->getAll();

        $angularModules['fileUpload'] = true;
        require(__DIR__ . '/../../../www/views/story-add.php');
    }

    /**
     * @param $loggedInUser
     * @return bool
     */
    private function userCanAddStory(User $loggedInUser):bool
    {
        $userCanAddStory = (bool)($loggedInUser AND ($loggedInUser->isCommunityAdmin() OR $loggedInUser->isEditorialAdmin()));
        return $userCanAddStory;
    }
}