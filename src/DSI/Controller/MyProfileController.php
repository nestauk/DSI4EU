<?php

namespace DSI\Controller;

use DSI\Entity\Image;
use DSI\Entity\UserLink;
use DSI\Entity\UserLink_Service;
use DSI\Repository\UserLinkRepository;
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
                    if ($_POST['step'] == 'step1') {
                        $updateUserBasicDetails = new UpdateUserBasicDetails();
                        $updateUserBasicDetails->data()->userID = $user->getId();
                        $updateUserBasicDetails->data()->firstName = $_POST['firstName'] ?? '';
                        $updateUserBasicDetails->data()->lastName = $_POST['lastName'] ?? '';
                        $updateUserBasicDetails->data()->bio = $_POST['bio'] ?? '';
                        $updateUserBasicDetails->data()->links = $_POST['links'] ?? [];
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
                    } elseif ($_POST['step'] == 'step2') {
                        $updateUserBasicDetails = new UpdateUserBasicDetails();
                        $updateUserBasicDetails->data()->userID = $user->getId();
                        $updateUserBasicDetails->data()->countryName = $_POST['countryName'] ?? '';
                        $updateUserBasicDetails->data()->cityName = $_POST['cityName'] ?? '';
                        $updateUserBasicDetails->data()->jobTitle = $_POST['jobTitle'] ?? '';
                        $updateUserBasicDetails->data()->company = $_POST['company'] ?? '';
                        $updateUserBasicDetails->data()->languages = $_POST['languages'] ?? [];
                        $updateUserBasicDetails->data()->skills = $_POST['skills'] ?? [];
                        $updateUserBasicDetails->exec();
                    } elseif ($_POST['step'] == 'step3') {
                        $updateUserBasicDetails = new UpdateUserBasicDetails();
                        $updateUserBasicDetails->data()->userID = $user->getId();
                        $updateUserBasicDetails->data()->projects = $_POST['projects'] ?? [];
                        $updateUserBasicDetails->data()->organisations = $_POST['organisations'] ?? [];
                        $updateUserBasicDetails->exec();
                    }

                    // $updateUserBasicDetails->data()->showEmail = $_POST['showEmail'] ?? false;

                    echo json_encode([
                        'response' => 'ok'
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
                        'response' => 'ok'
                    ]);
                    return;
                }
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'response' => 'error',
                    'errors' => $e->getErrors()
                ]);
                return;
            }

            $links = [];
            $userLinks = (new UserLinkRepository())->getByUserID($loggedInUser->getId());
            foreach ($userLinks AS $userLink) {
                if ($userLink->getLinkService() == UserLink_Service::Facebook)
                    $links['facebook'] = $userLink->getLink();
                if ($userLink->getLinkService() == UserLink_Service::Twitter)
                    $links['twitter'] = $userLink->getLink();
                if ($userLink->getLinkService() == UserLink_Service::GooglePlus)
                    $links['googleplus'] = $userLink->getLink();
                if ($userLink->getLinkService() == UserLink_Service::GitHub)
                    $links['github'] = $userLink->getLink();
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

                'links' => $links ? $links : '',
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