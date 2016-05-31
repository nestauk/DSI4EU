<?php

namespace DSI\Controller;

use DSI\Repository\ProjectPostCommentRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\UseCase\AddReplyToProjectPostComment;

class ProjectPostCommentController
{
    /** @var ProjectPostCommentController_Data */
    private $data;

    public function __construct()
    {
        $this->data = new ProjectPostCommentController_Data();
    }

    public function exec()
    {
        $loggedInUser = null;

        $authUser = new Auth();
        if ($authUser->isLoggedIn()) {
            $userRepo = new UserRepository();
            $loggedInUser = $userRepo->getById($authUser->getUserId());
        }

        $projectPostCommentRepo = new ProjectPostCommentRepository();
        $comment = $projectPostCommentRepo->getById($this->data()->commentID);

        try {
            if (isset($_POST['addReply'])) {
                $addReplyCmd = new AddReplyToProjectPostComment();
                $addReplyCmd->data()->reply = $_POST['reply'];
                $addReplyCmd->data()->projectPostComment = $comment;
                $addReplyCmd->data()->user = $loggedInUser;
                $addReplyCmd->exec();
                $reply = $addReplyCmd->getProjectPostCommentReply();

                echo json_encode([
                    'result' => 'ok',
                    'reply' => [
                        'id' => $reply->getId(),
                        'comment' => $reply->getComment(),
                        'time' => $reply->getTime(),
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
     * @return ProjectPostCommentController_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class ProjectPostCommentController_Data
{
    /** @var  int */
    public $commentID;

    /** @var string */
    public $format = 'json';
}