<?php
namespace DSI\Controller;

use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;
use DSI\UseCase\AppRegistration\AppRegistrationCreate;
use DSI\UseCase\CreateUser;
use DSI\UseCase\SendWelcomeEmailAfterRegistration;

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

            $registeredUser = $createUser->getUser();
            $loggedInUser = (new UserRepository())->getById($_GET['userID'] ?? 0);

            $createAppRegistration = new AppRegistrationCreate();
            $createAppRegistration->data()->loggedInUser = $loggedInUser;
            $createAppRegistration->data()->registeredUser = $registeredUser;
            $createAppRegistration->exec();

            $sendEmail = new SendWelcomeEmailAfterRegistration();
            $sendEmail->data()->emailAddress = $registeredUser->getEmail();
            $sendEmail->exec();

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