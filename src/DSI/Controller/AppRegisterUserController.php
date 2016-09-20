<?php
namespace DSI\Controller;

use DSI\Entity\User;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;
use DSI\UseCase\AppRegistration\AppRegistrationCreate;
use DSI\UseCase\CreatePasswordRecovery;
use DSI\UseCase\CreateUser;
use DSI\UseCase\SendWelcomeEmailToAppRegisteredUser;

class AppRegisterUserController
{
    public function exec()
    {
        try {
            $loggedInUser = (new UserRepository())->getById($_GET['userID'] ?? 0);
            $registeredUser = $this->createUser()->getUser();

            $this->createAppRegistration($loggedInUser, $registeredUser);
            $code = $this->createPasswordRecoveryCode($registeredUser);
            $this->sendWelcomeEmail($registeredUser, $code);

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

    /**
     * @return CreateUser
     */
    private function createUser():CreateUser
    {
        $createUser = new CreateUser();
        $createUser->data()->firstName = $_GET['firstName'] ?? '';
        $createUser->data()->lastName = $_GET['lastName'] ?? '';
        $createUser->data()->email = $_GET['email'] ?? '';
        $createUser->data()->jobTitle = $_GET['jobTitle'] ?? '';
        $createUser->data()->organisation = $_GET['organisation'] ?? '';
        $createUser->exec();
        return $createUser;
    }

    /**
     * @param $loggedInUser
     * @param $registeredUser
     */
    private function createAppRegistration($loggedInUser, $registeredUser)
    {
        $createAppRegistration = new AppRegistrationCreate();
        $createAppRegistration->data()->loggedInUser = $loggedInUser;
        $createAppRegistration->data()->registeredUser = $registeredUser;
        $createAppRegistration->exec();
    }

    /**
     * @param User $registeredUser
     * @return string
     */
    private function createPasswordRecoveryCode(User $registeredUser)
    {
        $passwordRecovery = new CreatePasswordRecovery();
        $passwordRecovery->data()->email = $registeredUser->getEmail();
        $passwordRecovery->data()->sendEmail = false;
        $passwordRecovery->exec();
        return $passwordRecovery->getPasswordRecovery()->getCode();
    }

    /**
     * @param User $registeredUser
     * @param string $code
     */
    private function sendWelcomeEmail(User $registeredUser, string $code)
    {
        $sendEmail = new SendWelcomeEmailToAppRegisteredUser();
        $sendEmail->data()->code = $code;
        $sendEmail->data()->emailAddress = $registeredUser->getEmail();
        $sendEmail->exec();
    }
}