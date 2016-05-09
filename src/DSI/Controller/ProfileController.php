<?php

namespace DSI\Controller;

use DSI\Repository\UserLanguageRepository;
use DSI\Repository\UserLinkRepository;
use DSI\Repository\UserRepository;
use DSI\Repository\UserSkillRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\AddLanguageToUser;
use DSI\UseCase\AddLinkToUser;
use DSI\UseCase\AddTagToProject;
use DSI\UseCase\RemoveLanguageFromUser;
use DSI\UseCase\RemoveLinkFromUser;
use DSI\UseCase\RemoveSkillFromUser;
use DSI\UseCase\UpdateUserBio;

class ProfileController
{
    /** @var ProfileController_Data */
    private $data;

    public function __construct()
    {
        $this->data = new ProfileController_Data();
    }

    public function exec()
    {
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo(URL::login());

        $user = $this->getUserFromURL();

        if ($this->data()->format == 'json') {
            $userSkillRepo = new UserSkillRepository();
            $userLangRepo = new UserLanguageRepository();
            $userLinkRepo = new UserLinkRepository();

            if (isset($_POST['addSkill'])) {
                try {
                    $addSkillToUser = new AddTagToProject();
                    $addSkillToUser->data()->projectID = $user->getId();
                    $addSkillToUser->data()->tag = $_POST['addSkill'];
                    $addSkillToUser->exec();
                } catch (ErrorHandler $e) {
                }
            } elseif (isset($_POST['removeSkill'])) {
                try {
                    $removeSkillFromUser = new RemoveSkillFromUser();
                    $removeSkillFromUser->data()->userID = $user->getId();
                    $removeSkillFromUser->data()->skill = $_POST['removeSkill'];
                    $removeSkillFromUser->exec();
                } catch (ErrorHandler $e) {
                }
            } elseif (isset($_POST['addLanguage'])) {
                try {
                    $addSkillToUser = new AddLanguageToUser();
                    $addSkillToUser->data()->userID = $user->getId();
                    $addSkillToUser->data()->language = $_POST['addLanguage'];
                    $addSkillToUser->exec();
                } catch (ErrorHandler $e) {
                }
            } elseif (isset($_POST['removeLanguage'])) {
                try {
                    $removeLanguageFromUser = new RemoveLanguageFromUser();
                    $removeLanguageFromUser->data()->userID = $user->getId();
                    $removeLanguageFromUser->data()->language = $_POST['removeLanguage'];
                    $removeLanguageFromUser->exec();
                } catch (ErrorHandler $e) {
                }
            } elseif (isset($_POST['updateBio'])) {
                try {
                    $updateUserBio = new UpdateUserBio();
                    $updateUserBio->data()->userID = $user->getId();
                    $updateUserBio->data()->bio = $_POST['bio'];
                    $updateUserBio->exec();
                } catch (ErrorHandler $e) {
                }
            } elseif (isset($_POST['addLink'])) {
                try {
                    $addLinkToUser = new AddLinkToUser();
                    $addLinkToUser->data()->userID = $user->getId();
                    $addLinkToUser->data()->link = $_POST['addLink'];
                    $addLinkToUser->exec();
                } catch (ErrorHandler $e) {
                }
            } elseif (isset($_POST['removeLink'])) {
                try {
                    $removeLinkFromUser = new RemoveLinkFromUser();
                    $removeLinkFromUser->data()->userID = $user->getId();
                    $removeLinkFromUser->data()->link = $_POST['removeLink'];
                    $removeLinkFromUser->exec();
                } catch (ErrorHandler $e) {
                }
            } else {
                echo json_encode([
                    'tags' => $userSkillRepo->getSkillsNameByUserID($user->getId()),
                    'languages' => $userLangRepo->getLanguagesNameByUserID($user->getId()),
                    'links' => $userLinkRepo->getLinksByUserID($user->getId()),
                    'user' => [
                        'bio' => $user->getBio()
                    ],
                ]);
            }
        } else {
            $userID = $user->getId();
            $loggedInUser = (new UserRepository())->getById($authUser->getUserId());
            require __DIR__ . '/../../../www/profile.php';
        }
    }

    public function data()
    {
        return $this->data;
    }

    /**
     * @return \DSI\Entity\User
     */
    protected function getUserFromURL()
    {
        $userRepo = new UserRepository();
        if (ctype_digit($this->data()->userURL)) {
            $user = $userRepo->getById((int)$this->data()->userURL);
            return $user;
        } else {
            $user = $userRepo->getByProfileURL($this->data()->userURL);
            return $user;
        }
    }
}

class ProfileController_Data
{
    public $userURL,
        $format = 'html';
}