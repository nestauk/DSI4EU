<?php

namespace DSI\Controller;

use DSI\Repository\UserRepo;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use Services\URL;
use DSI\UseCase\SendFeedback;

class FeedbackController
{
    /** @var string */
    public $format = 'html';

    public function exec()
    {
        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();

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

        $pageTitle = 'Feedback';
        require __DIR__ . '/../../../www/views/feedback.php';
    }
}