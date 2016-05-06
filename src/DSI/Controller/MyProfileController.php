<?php

namespace DSI\Controller;

use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\UpdateUserBasicDetails;
use DSI\UseCase\UpdateUserEmailAddress;
use DSI\UseCase\UpdateUserPassword;

class MyProfileController
{
    /** @var MyProfileController_Data */
    private $data;

    public function __construct()
    {
        $this->data = new MyProfileController_Data();
    }

    public function exec()
    {
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo(URL::login());

        $userRepo = new UserRepository();
        $user = $userRepo->getById(
            $authUser->getUserId()
        );

        if ($this->data->format == 'json') {

            if (isset($_POST['saveDetails'])) {
                try {
                    $updateUserBasicDetails = new UpdateUserBasicDetails();
                    $updateUserBasicDetails->data()->userID = $user->getId();
                    $updateUserBasicDetails->data()->firstName = $_POST['details']['firstName'];
                    $updateUserBasicDetails->data()->lastName = $_POST['details']['lastName'];
                    $updateUserBasicDetails->data()->location = $_POST['details']['location'];
                    $updateUserBasicDetails->exec();

                    $updateUserEmail = new UpdateUserEmailAddress();
                    $updateUserEmail->data()->userID = $user->getId();
                    $updateUserEmail->data()->email = $_POST['details']['email'];
                    $updateUserEmail->exec();

                    echo json_encode([
                        'response' => 'ok'
                    ]);
                } catch (ErrorHandler $e) {
                    echo json_encode([
                        'response' => 'error',
                        'errors' => $e->getErrors()
                    ]);
                }
                die();
            }

            if (isset($_POST['changePassword'])) {
                try {
                    $updateUserPassword = new UpdateUserPassword();
                    $updateUserPassword->data()->userID = $user->getId();
                    $updateUserPassword->data()->password = (string)($_POST['password'] ?? '');
                    $updateUserPassword->data()->retypePassword = (string)($_POST['retypePassword'] ?? '');
                    $updateUserPassword->exec();
                    echo json_encode([
                        'result' => 'ok'
                    ]);
                } catch (ErrorHandler $e) {
                    echo json_encode([
                        'result' => 'error',
                        'errors' => $e->getErrors(),
                    ]);
                }
                die();
            }

            echo json_encode([
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
                'email' => $user->getEmail(),
                'location' => $user->getLocation(),
                'profilePic' => $user->getProfilePicOrDefault(),
            ]);
            die();
        } else {
            go_to(URL::profile($user->getProfileURL() ?? $user->getId()));
        }
    }

    public
    function data()
    {
        return $this->data;
    }
}

class MyProfileController_Data
{
    public $format = 'html';
}