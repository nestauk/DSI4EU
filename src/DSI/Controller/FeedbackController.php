<?php

namespace DSI\Controller;

use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\SendFeedback;

class FeedbackController
{
    /** @var string */
    public $format = 'html';

    public function exec()
    {
        $authUser = new Auth();
        // $authUser->ifNotLoggedInRedirectTo(URL::login());

        if ($this->format == 'json') {
            try {
                $sendFeedback = new SendFeedback();
                $sendFeedback->data()->name = $_POST['name'] ?? '';
                $sendFeedback->data()->email = $_POST['email'] ?? '';
                $sendFeedback->data()->message = $_POST['message'] ?? '';
                $sendFeedback->data()->sendEmail = true;
                $sendFeedback->exec();
                echo json_encode(['code' => 'ok']);
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors(),
                ]);
            }
            return;
        }

        if ($authUser->getUserId()) {
            $loggedInUser = (new UserRepository())->getById($authUser->getUserId());
        } else {
            $loggedInUser = null;
        }

        $data = [
            'loggedInUser' => $loggedInUser,
        ];
        $pageTitle = 'Feedback';
        require __DIR__ . '/../../../www/feedback.php';
    }
}