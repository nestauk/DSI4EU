<?php
namespace DSI\Controller;

use DSI\Service\ErrorHandler;
use DSI\UseCase\CreateUser;

class AppRegisterUserController
{
    public function exec()
    {
        try {
            $createUser = new CreateUser();
            $createUser->data()->firstName = $_GET['firstName'] ?? '';
            $createUser->data()->lastName = $_GET['lastName'] ?? '';
            $createUser->data()->email = $_GET['email'] ?? '';
            $createUser->data()->jobTitle = $_GET['jobTitle'] ?? '';
            $createUser->data()->organisation = $_GET['organisation'] ?? '';
            $createUser->exec();

            echo json_encode([
                'code' => 'ok',
            ]);
            return;
        } catch (ErrorHandler $e) {
            echo json_encode([
                'code' => 'error',
                'errors' => $e->getTaggedError('email'),
            ]);
            return;
        }
    }
}