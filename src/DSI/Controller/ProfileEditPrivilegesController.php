<?php

namespace DSI\Controller;

use DSI\AccessDenied;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class ProfileEditPrivilegesController
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
        $sessionKey = 'secureCode-profileEditPrivilege';

        $userRepo = new UserRepository();
        $user = $userRepo->getById($this->userID);

        if (!$loggedInUser->isSysAdmin())
            throw new AccessDenied('You are not allowed to access this page');

        if (isset($_POST['save'])) {
            if ($_POST['secureCode'] == $_SESSION[$sessionKey]) {
                $user->setRole($_POST['userRole']);
                $userRepo->save($user);
                go_to();
            }
        }

        $securityCode = base64_encode(openssl_random_pseudo_bytes(128));
        $_SESSION[$sessionKey] = $securityCode;

        require __DIR__ . '/../../../www/views/profile-edit-privileges.php';
    }
}