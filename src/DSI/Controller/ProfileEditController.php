<?php

namespace DSI\Controller;

use DSI\AccessDenied;
use DSI\Entity\Image;
use DSI\Entity\User;
use DSI\Entity\UserLink_Service;
use DSI\Repository\LanguageRepo;
use DSI\Repository\OrganisationMemberRepo;
use DSI\Repository\OrganisationRepoInAPC;
use DSI\Repository\ProjectMemberRepo;
use DSI\Repository\ProjectRepoInAPC;
use DSI\Repository\SkillRepo;
use DSI\Repository\UserLanguageRepo;
use DSI\Repository\UserLinkRepo;
use DSI\Repository\UserRepo;
use DSI\Repository\UserSkillRepo;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use Services\URL;
use DSI\UseCase\UpdateUserBasicDetails;
use DSI\UseCase\UpdateUserEmailAddress;
use DSI\UseCase\UpdateUserPassword;
use DSI\UseCase\UpdateUserProfilePicture;
use Services\View;

class ProfileEditController
{
    /** @var int */
    public $userID;

    /** @var string */
    public $format = 'html';

    public function __construct()
    {
    }

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());
        $loggedInUser = $authUser->getUser();

        if (!$this->userID)
            $this->userID = $loggedInUser->getId();

        $userRepo = new UserRepo();
        $user = $userRepo->getById($this->userID);

        if (!$this->loggedInUserCanManageUser($loggedInUser, $user))
            throw new AccessDenied('You are not allowed to access this page');

        if ($this->format == 'json') {
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
                        $updateUserEmail->data()->email = $_POST['email'] ?? '';
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
            $userLinks = (new UserLinkRepo())->getByUser($user);
            foreach ($userLinks AS $userLink) {
                if ($userLink->getLinkService() == UserLink_Service::Facebook)
                    $links['facebook'] = $userLink->getLink();
                if ($userLink->getLinkService() == UserLink_Service::Twitter)
                    $links['twitter'] = $userLink->getLink();
                if ($userLink->getLinkService() == UserLink_Service::GooglePlus)
                    $links['googleplus'] = $userLink->getLink();
                if ($userLink->getLinkService() == UserLink_Service::GitHub)
                    $links['github'] = $userLink->getLink();
                if ($userLink->getLinkService() == UserLink_Service::Other)
                    $links['other'] = $userLink->getLink();
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
        }

        $languages = (new LanguageRepo())->getAll();
        $userLanguages = (new UserLanguageRepo())->getLanguageIDsForUser($user->getId());
        $skills = (new SkillRepo())->getAll();
        $userSkills = (new UserSkillRepo())->getSkillsNameByUserID($user->getId());
        $projects = (new ProjectRepoInAPC())->getAll();
        $userProjects = (new ProjectMemberRepo())->getProjectIDsForMember($user->getId());
        $organisations = (new OrganisationRepoInAPC())->getAll();
        $userOrganisations = (new OrganisationMemberRepo())->getOrganisationIDsForMember($user->getId());

        $angularModules['fileUpload'] = true;
        View::setPageTitle('Create your profile - DSI4EU');
        require __DIR__ . '/../../../www/views/profile-edit.php';
    }

    /**
     * @param $loggedInUser
     * @param $user
     * @return bool
     */
    private function loggedInUserCanManageUser(User $loggedInUser, User $user)
    {
        if ($loggedInUser->getId() == $user->getId())
            return true;

        if ($loggedInUser->isSysAdmin())
            return true;

        return false;
    }
}