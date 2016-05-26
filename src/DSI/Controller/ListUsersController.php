<?php

namespace DSI\Controller;

use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class ListUsersController
{
    public function exec()
    {
        $authUser = new Auth();
        if ($authUser->isLoggedIn()) {

            $userRepo = new UserRepository();
            $users = [];
            foreach ($userRepo->getAll() AS $user) {
                $users[] = [
                    'id' => $user->getId(),
                    'text' => $user->getFirstName() . ' ' . $user->getLastName(),
                    'profilePic' => $user->getProfilePicOrDefault(),
                    'firstName' => $user->getFirstName(),
                    'lastName' => $user->getLastName(),
                ];
            }

            echo json_encode($users);
            return;
        } else {
            echo json_encode([]);
            return;
        }
    }
}