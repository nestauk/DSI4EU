<?php

namespace DSI\Controller;

use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\CreateStory;

class CreateStoryController
{
    /** @var string */
    public $responseFormat = 'html';

    public function __construct()
    {
    }

    public function exec()
    {
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo(URL::login());

        $loggedInUser = (new UserRepository())->getById($authUser->getUserId());

        if ($this->responseFormat == 'json') {
            try {
                $createStory = new CreateStory();
                $createStory->data()->writerID = $loggedInUser->getId();
                $createStory->exec();
                $story = $createStory->getStory();

                echo json_encode([
                    'result' => 'ok',
                    'url' => URL::story($story->getId(), $story->getTitle()),
                ]);
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'result' => 'error',
                    'errors' => $e->getErrors(),
                ]);
            }
            die();
        }

        require(__DIR__ . '/../../../www/story-add.php');
    }
}