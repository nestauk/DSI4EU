<?php

namespace DSI\Controller;

use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\UpdateUserProfilePicture;

class PersonalDetailsController
{
    public function __construct()
    {
    }

    public function exec()
    {
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo(URL::login());

        $user = (new UserRepository())->getById($authUser->getUserId());
        $userID = $user->getId();

        if (isset($_FILES['file'])) {
            try {
                $updateUserProfilePicture = new UpdateUserProfilePicture();
                $updateUserProfilePicture->data()->userID = $userID;
                $updateUserProfilePicture->data()->filePath = $_FILES['file']['tmp_name'];
                $updateUserProfilePicture->data()->fileName = $_FILES['file']['name'];
                $updateUserProfilePicture->exec();

                echo json_encode([
                    'result' => 'ok',
                    'imgPath' => $updateUserProfilePicture->getProfilePic(),
                ]);
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'result' => 'error',
                    'errors' => $e->getErrors(),
                ]);
            }
            die();
        }

        $loggedInUser = (new UserRepository())->getById($authUser->getUserId());
        require __DIR__ . '/../../../www/personal-details.php';
    }
}