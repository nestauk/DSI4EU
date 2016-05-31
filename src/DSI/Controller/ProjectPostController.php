<?php

namespace DSI\Controller;

use DSI\Repository\ProjectPostRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\UseCase\AddCommentToProjectPost;

class ProjectPostController
{
    /** @var  ProjectPostController_Data */
    private $data;

    public function __construct()
    {
        $this->data = new ProjectPostController_Data();
    }

    public function exec()
    {
        $loggedInUser = null;

        $authUser = new Auth();
        if ($authUser->isLoggedIn()) {
            $userRepo = new UserRepository();
            $loggedInUser = $userRepo->getById($authUser->getUserId());
        }

        $projectPostRepo = new ProjectPostRepository();
        $post = $projectPostRepo->getById($this->data()->postID);
        $project = $post->getProject();

        $isOwner = false;

        if ($loggedInUser) {
            if ($project->getOwner()->getId() == $loggedInUser->getId())
                $isOwner = true;
        }

        try {
            if (isset($_POST['addPostComment'])) {
                $addCommentCmd = new AddCommentToProjectPost();
                $addCommentCmd->data()->comment = $_POST['comment'];
                $addCommentCmd->data()->projectPost = $post;
                $addCommentCmd->data()->user = $loggedInUser;
                $addCommentCmd->exec();
                $comment = $addCommentCmd->getProjectPostComment();

                echo json_encode([
                    'result' => 'ok',
                    'comment' => [
                        'id' => $comment->getId(),
                        'comment' => $comment->getComment(),
                        'time' => $comment->getJsTime(),
                        'user' => [
                            'name' => $loggedInUser->getFullName(),
                            'profilePic' => $loggedInUser->getProfilePicOrDefault(),
                        ],
                    ],
                ]);
                return;
            }
        } catch (ErrorHandler $e) {
            echo json_encode([
                'result' => 'error',
                'errors' => $e->getErrors()
            ]);
            die();
        }
    }

    /**
     * @return ProjectPostController_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class ProjectPostController_Data
{
    /** @var  int */
    public $postID;

    /** @var string */
    public $format = 'json';
}