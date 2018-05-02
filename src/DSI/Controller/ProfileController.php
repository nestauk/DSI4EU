<?php

namespace DSI\Controller;

use DSI\Entity\OrganisationMember;
use DSI\Entity\ProjectMember;
use DSI\Entity\User;
use DSI\Repository\OrganisationMemberRepo;
use DSI\Repository\OrganisationRepoInAPC;
use DSI\Repository\ProjectMemberRepo;
use DSI\Repository\ProjectRepoInAPC;
use DSI\Repository\UserLanguageRepo;
use DSI\Repository\UserLinkRepo;
use DSI\Repository\UserRepo;
use DSI\Repository\UserSkillRepo;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\Mailer;
use Services\URL;
use DSI\UseCase\AddLanguageToUser;
use DSI\UseCase\AddLinkToUser;
use DSI\UseCase\AddMemberRequestToOrganisation;
use DSI\UseCase\AddMemberRequestToProject;
use DSI\UseCase\AddSkillToUser;
use DSI\UseCase\RememberPermanentLogin;
use DSI\UseCase\RemoveLanguageFromUser;
use DSI\UseCase\RemoveLinkFromUser;
use DSI\UseCase\RemoveSkillFromUser;
use DSI\UseCase\SecureCode;
use DSI\UseCase\SendEmailToCommunityAdmins;
use DSI\UseCase\UpdateUserBasicDetails;
use DSI\UseCase\UpdateUserBio;
use DSI\UseCase\Users\DisableUser;
use DSI\UseCase\Users\EnableUser;

class ProfileController
{
    /** @var string */
    private $userURL,
        $format;

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());
        $loggedInUser = $authUser->getUser();

        if (!$this->userURL)
            go_to($urlHandler->profile($loggedInUser));

        $user = $this->getUserFromURL($this->userURL);

        if (isset($_POST['getSecureCode']))
            return $this->getSecureCode();

        $canManageUsers = $this->canManageUsers($loggedInUser);
        $askForPermanentLogin = $this->askForPermanentLogin();

        if ($canManageUsers)
            if (isset($_POST['setUserDisabled']))
                return $this->setUserStatus($loggedInUser, $user, $urlHandler);

        if (isset($_POST['report']))
            return $this->reportUser($loggedInUser, $user, $urlHandler);

        $userID = $user->getId();
        $isOwner = ($user->getId() == $loggedInUser->getId());

        if ($this->format == 'json') {
            $userSkillRepo = new UserSkillRepo();
            $userLangRepo = new UserLanguageRepo();
            $userLinkRepo = new UserLinkRepo();
            $projectMemberRepo = new ProjectMemberRepo();
            $organisationMemberRepo = new OrganisationMemberRepo();

            if ($isOwner) {
                try {
                    if (isset($_POST['permanentLogin'])) {
                        return $this->permanentLogin($loggedInUser);
                    } elseif (isset($_POST['addSkill'])) {
                        $addSkillToUser = new AddSkillToUser();
                        $addSkillToUser->data()->userID = $user->getId();
                        $addSkillToUser->data()->skill = $_POST['addSkill'];
                        $addSkillToUser->exec();
                        return null;
                    } elseif (isset($_POST['removeSkill'])) {
                        $removeSkillFromUser = new RemoveSkillFromUser();
                        $removeSkillFromUser->data()->userID = $user->getId();
                        $removeSkillFromUser->data()->skill = $_POST['removeSkill'];
                        $removeSkillFromUser->exec();
                        return null;
                    } elseif (isset($_POST['addLanguage'])) {
                        $addSkillToUser = new AddLanguageToUser();
                        $addSkillToUser->data()->userID = $user->getId();
                        $addSkillToUser->data()->language = $_POST['addLanguage'];
                        $addSkillToUser->exec();
                        return null;
                    } elseif (isset($_POST['removeLanguage'])) {
                        $removeLanguageFromUser = new RemoveLanguageFromUser();
                        $removeLanguageFromUser->data()->userID = $user->getId();
                        $removeLanguageFromUser->data()->language = $_POST['removeLanguage'];
                        $removeLanguageFromUser->exec();
                        return null;
                    } elseif (isset($_POST['updateBio'])) {
                        $updateUserBio = new UpdateUserBio();
                        $updateUserBio->data()->userID = $user->getId();
                        $updateUserBio->data()->bio = $_POST['bio'] ?? '';
                        $updateUserBio->exec();
                        echo json_encode(['code' => 'ok']);
                        return null;
                    } elseif (isset($_POST['addLink'])) {
                        $addLinkToUser = new AddLinkToUser();
                        $addLinkToUser->data()->userID = $user->getId();
                        $addLinkToUser->data()->link = $_POST['addLink'];
                        $addLinkToUser->exec();
                        return null;
                    } elseif (isset($_POST['removeLink'])) {
                        $removeLinkFromUser = new RemoveLinkFromUser();
                        $removeLinkFromUser->data()->userID = $user->getId();
                        $removeLinkFromUser->data()->link = $_POST['removeLink'];
                        $removeLinkFromUser->exec();
                        return null;
                    } elseif (isset($_POST['updateBasicDetails'])) {
                        $updateUserBasicDetails = new UpdateUserBasicDetails();
                        $updateUserBasicDetails->data()->userID = $user->getId();
                        $updateUserBasicDetails->data()->firstName = $_POST['firstName'] ?? '';
                        $updateUserBasicDetails->data()->lastName = $_POST['lastName'] ?? '';
                        $updateUserBasicDetails->data()->cityName = $_POST['location'] ?? '';
                        $updateUserBasicDetails->data()->jobTitle = $_POST['jobTitle'] ?? '';
                        $updateUserBasicDetails->exec();
                        echo json_encode(['code' => 'ok']);
                        return null;
                    } elseif (isset($_POST['joinProject'])) {
                        $joinProject = new AddMemberRequestToProject();
                        $joinProject->data()->projectID = $_POST['project'];
                        $joinProject->data()->userID = $loggedInUser->getId();
                        $joinProject->exec();
                        echo json_encode(['code' => 'ok']);
                        return null;
                    } elseif (isset($_POST['joinOrganisation'])) {
                        $joinOrganisation = new AddMemberRequestToOrganisation();
                        $joinOrganisation->data()->organisationID = $_POST['organisation'];
                        $joinOrganisation->data()->userID = $loggedInUser->getId();
                        $joinOrganisation->exec();
                        echo json_encode(['code' => 'ok']);
                        return null;
                    }
                } catch (ErrorHandler $e) {
                    echo json_encode([
                        'code' => 'error',
                        'errors' => $e->getErrors()
                    ]);
                    return null;
                }
            }

            echo json_encode([
                'tags' => $userSkillRepo->getSkillsNameByUserID($user->getId()),
                'languages' => $userLangRepo->getLanguagesNameByUserID($user->getId()),
                'links' => $userLinkRepo->getLinksByUserID($user->getId()),
                'user' => [
                    'firstName' => $user->getFirstName(),
                    'lastName' => $user->getLastName(),
                    'jobTitle' => $user->getJobTitle(),
                    'location' => $user->getCityName(),
                    'bio' => $user->getBio(),
                    'profilePic' => $user->getProfilePicOrDefault(),
                    'projects' => array_map(function (ProjectMember $projectMember) use ($projectMemberRepo, $urlHandler) {
                        $project = $projectMember->getProject();
                        return [
                            'name' => $project->getName(),
                            'url' => $urlHandler->project($project),
                            'membersCount' => count($projectMemberRepo->getByProject($project)),
                        ];
                    }, $projectMemberRepo->getByMemberID($user->getId())),
                    'organisations' => array_map(function (OrganisationMember $organisationMember) use ($organisationMemberRepo, $urlHandler) {
                        $organisation = $organisationMember->getOrganisation();
                        return [
                            'name' => $organisation->getName(),
                            'url' => $urlHandler->organisation($organisation),
                            'membersCount' => count($organisationMemberRepo->getByOrganisation($organisation)),
                        ];
                    }, $organisationMemberRepo->getByMemberID($user->getId())),
                ],
            ]);
        } else {
            $projects = (new ProjectRepoInAPC())->getAll();
            $organisations = (new OrganisationRepoInAPC())->getAll();
            $userLinks = (new UserLinkRepo())->getLinksByUserID($userID);
            $angularModules['fileUpload'] = true;
            require __DIR__ . '/../../../www/views/profile.php';
        }

        return null;
    }

    /**
     * @return \DSI\Entity\User
     */
    protected function getUserFromURL($url)
    {
        $userRepo = new UserRepo();
        if (ctype_digit($url)) {
            $user = $userRepo->getById((int)$url);
            return $user;
        } else {
            $user = $userRepo->getByProfileURL($url);
            return $user;
        }
    }

    private function getSecureCode()
    {
        $genSecureCode = new SecureCode();
        $genSecureCode->exec();
        echo json_encode([
            'code' => 'ok',
            'secureCode' => $genSecureCode->getCode(),
        ]);
        return null;
    }

    /**
     * @param User $loggedInUser
     * @param User $user
     * @param URL $urlHandler
     * @return null
     */
    private function setUserStatus(User $loggedInUser, User $user, URL $urlHandler)
    {
        $genSecureCode = new SecureCode();
        if ($genSecureCode->checkCode($_POST['secureCode'])) {
            try {
                if ($_POST['setUserDisabled'] == true) {
                    $userSetStatus = new DisableUser();
                } else {
                    $userSetStatus = new EnableUser();
                }
                $userSetStatus->data()->executor = $loggedInUser;
                $userSetStatus->data()->userID = $user->getId();
                $userSetStatus->exec();

                echo json_encode([
                    'code' => 'ok',
                    'url' => $urlHandler->profile($user)
                ]);
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors()
                ]);
            }
        }

        return null;
    }

    /**
     * @param User $loggedInUser
     * @param User $user
     * @param URL $urlHandler
     * @return null
     */
    private function reportUser(User $loggedInUser, User $user, URL $urlHandler)
    {
        $genSecureCode = new SecureCode();
        if ($genSecureCode->checkCode($_POST['secureCode'])) {
            try {
                ob_start(); ?>
                User: <?php echo show_input($loggedInUser->getFullName()) ?>
                (<a href="https://<?php echo SITE_DOMAIN . $urlHandler->profile($loggedInUser) ?>">View profile</a>)
                <br/>
                Reported Profile: <?php echo show_input($user->getFullName()) ?>
                (<a href="https://<?php echo SITE_DOMAIN . $urlHandler->profile($user) ?>">View profile</a>)
                <br/>
                Reason: <?php echo show_input($_POST['reason']) ?>
                <br/>
                <?php $message = ob_get_clean();

                $mail = new Mailer();
                $mail->Subject = 'Profile Report on DSI4EU';
                $mail->wrapMessageInTemplate([
                    'header' => 'Profile Report on DSI4EU',
                    'body' => $message
                ]);

                $sendMassMessageToAdmins = new SendEmailToCommunityAdmins();
                $sendMassMessageToAdmins->data()->executor = $loggedInUser;
                $sendMassMessageToAdmins->data()->mail = $mail;
                $sendMassMessageToAdmins->exec();

                echo json_encode([
                    'code' => 'ok'
                ]);
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors()
                ]);
            }
        }

        return null;
    }

    /**
     * @param $loggedInUser
     * @return bool
     */
    private function canManageUsers(User $loggedInUser)
    {
        return (bool)$loggedInUser->isCommunityAdmin();
    }

    /**
     * @return bool
     */
    public function askForPermanentLogin()
    {
        return (isset($_GET['src']) AND $_GET['src'] == 'login');
    }

    /**
     * @param string $userURL
     */
    public function setUserURL(string $userURL)
    {
        $this->userURL = $userURL;
    }

    /**
     * @param string $format
     */
    public function setFormat(string $format)
    {
        $this->format = $format;
    }

    /**
     * @param $user
     * @return null
     */
    public function permanentLogin($user)
    {
        try {
            $exec = new RememberPermanentLogin();
            $exec->setUser($user);
            $exec->exec();

            echo json_encode([
                'code' => 'ok'
            ]);
        } catch (ErrorHandler $e) {
            echo json_encode([
                'code' => 'error',
                'errors' => $e->getErrors()
            ]);
        }

        return null;
    }
}