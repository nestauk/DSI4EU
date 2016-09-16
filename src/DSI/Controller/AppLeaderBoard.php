<?php
namespace DSI\Controller;

use DSI\Repository\AppRegistrationRepository;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;
use DSI\Service\SQL;

class AppLeaderBoard
{
    public function exec()
    {
        try {
            $appRegistrationRepository = new AppRegistrationRepository();
            $table = $appRegistrationRepository->getDbTable();
            $userRepo = new UserRepository();

            $query = new SQL("SELECT loggedInUserID, count(*) AS signups FROM `{$table}` GROUP BY loggedInUserID");
            $leaderBoard = $query->fetch_all();

            echo json_encode([
                'code' => 'ok',
                'leaderBoard' => array_map(function($row) use ($userRepo){
                    $user = $userRepo->getById($row['loggedInUserID']);
                    return [
                        'user' => [
                            'name' => $user->getFullName(),
                            'organisation' => $user->getCompany(),
                            'profilePic' => $user->getProfilePicOrDefault(),
                        ],
                        'signups' => $row['signups']
                    ];
                }, $leaderBoard),
            ]);
            return;
        } catch (ErrorHandler $e) {
            echo json_encode([
                'code' => 'error',
                'errors' => $e->getTaggedError('email'),
            ]);
            return;
        }
    }
}