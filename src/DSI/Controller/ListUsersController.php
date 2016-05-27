<?php

namespace DSI\Controller;

use DSI\Repository\UserRepository;
use DSI\Service\Auth;

class ListUsersController
{
    public function exec()
    {
        $authUser = new Auth();
        $users = [];
        if ($authUser->isLoggedIn()) {
            $userRepo = new UserRepository();
            foreach ($userRepo->getAll() AS $user) {
                $users[] = [
                    'id' => $user->getId(),
                    'text' => $user->getFirstName() . ' ' . $user->getLastName(),
                    'profilePic' => $user->getProfilePicOrDefault(),
                    'firstName' => $user->getFirstName(),
                    'lastName' => $user->getLastName(),
                ];
            }
        }

        echo json_encode($users);
        return;
    }
}