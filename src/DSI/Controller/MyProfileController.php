<?php

namespace DSI\Controller;

use DSI\Entity\Image;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\UpdateUserBasicDetails;
use DSI\UseCase\UpdateUserEmailAddress;
use DSI\UseCase\UpdateUserPassword;
use DSI\UseCase\UpdateUserProfilePicture;

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

        $loggedInUser = (new UserRepository())->getById($authUser->getUserId());
        $user = $loggedInUser;

        if ($this->data->format == 'json') {

            try {
                if (isset($_POST['saveDetails'])) {
                    $updateUserBasicDetails = new UpdateUserBasicDetails();
                    $updateUserBasicDetails->data()->userID = $user->getId();
                    $updateUserBasicDetails->data()->firstName = $_POST['firstName'] ?? '';
                    $updateUserBasicDetails->data()->lastName = $_POST['lastName'] ?? '';
                    $updateUserBasicDetails->data()->showEmail = $_POST['showEmail'] ?? false;
                    $updateUserBasicDetails->data()->cityName = $_POST['cityName'] ?? '';
                    $updateUserBasicDetails->data()->countryName = $_POST['countryName'] ?? '';
                    $updateUserBasicDetails->data()->jobTitle = $_POST['jobTitle'] ?? '';
                    $updateUserBasicDetails->data()->company = $_POST['company'] ?? '';
                    $updateUserBasicDetails->data()->bio = $_POST['bio'] ?? '';
                    $updateUserBasicDetails->exec();

                    $updateUserEmail = new UpdateUserEmailAddress();
                    $updateUserEmail->data()->userID = $user->getId();
                    $updateUserEmail->data()->email = $_POST['email'];
                    $updateUserEmail->exec();

                    if ($_POST['profilePic'] != Image::PROFILE_PIC_URL . $user->getProfilePicOrDefault()) {
                        $updateUserEmail = new UpdateUserProfilePicture();
                        $updateUserEmail->data()->userID = $user->getId();
                        $updateUserEmail->data()->fileName = basename($_POST['profilePic']);
                        $updateUserEmail->exec();
                    }

                    echo json_encode([
                        'response' => 'ok',
                        'message' => [
                            'title' => 'Success!',
                            'text' => 'Your details have been successfully saved',
                        ],
                    ]);
                    return;
                }

                if (isset($_POST['changePassword'])) {
                    $updateUserPassword = new UpdateUserPassword();
                    $updateUserPassword->data()->userID = $user->getId();
                    $updateUserPassword->data()->password = (string)($_POST['password'] ?? '');
                    $updateUserPassword->data()->retypePassword = (string)($_POST['retypePassword'] ?? '');
                    $updateUserPassword->exec();
                    echo json_encode([
                        'result' => 'ok'
                    ]);
                    return;
                }
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'response' => 'error',
                    'errors' => $e->getErrors()
                ]);
            }

            echo json_encode([
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
                'email' => $user->getEmail(),
                'showEmail' => $user->canShowEmail(),

                'jobTitle' => $user->getJobTitle(),
                'company' => $user->getCompany(),

                'cityName' => $user->getCityName(),
                'countryName' => $user->getCountryName(),

                'bio' => $user->getBio(),
                'profilePic' => Image::PROFILE_PIC_URL . $user->getProfilePicOrDefault(),
            ]);

            return;
        } else {
            go_to(URL::profile($user->getProfileURL() ?? $user->getId()));
        }
    }

    public function data()
    {
        return $this->data;
    }
}

class MyProfileController_Data
{
    public $format = 'html';
}