<?php

namespace Controllers\API;

use DSI\Entity\User;
use DSI\Repository\UserRepo;
use DSI\Service\Auth;
use Services\URL;
use Services\Response;
use Services\JsonResponse;

class EmailSubscribersApiController
{
    /** @var URL */
    private $urlHandler;

    /** @var User */
    private $loggedInUser;

    /** @var Auth */
    private $authUser;

    public function exec()
    {
        $this->urlHandler = new URL();
        $this->authUser = new Auth();
        $this->loggedInUser = $this->authUser->getUserIfLoggedIn();

        if (!$this->loggedInUser OR !$this->loggedInUser->isSysAdmin())
            return (new Response('UNAUTHORIZED', Response::HTTP_UNAUTHORIZED))->send();

        $users = (new UserRepo())->getAllSubscribedForEmail();
        return (new JsonResponse(array_map(function (User $user) {
            return [
                'id' => $user->getId(),
                'first_name' => $user->getFirstName(),
                'last_name' => $user->getLastName(),
                'email' => $user->getEmail(),
            ];
        }, $users)))->send();
    }
}