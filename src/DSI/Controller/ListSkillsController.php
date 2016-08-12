<?php

namespace DSI\Controller;

use DSI\Repository\SkillRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class ListSkillsController
{
    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());

        $skillRepo = new SkillRepository();
        $skills = [];
        foreach($skillRepo->getAll() AS $skill){
            $skills[] = [
                'id' => $skill->getName(),
                'text' => $skill->getName(),
            ];
        }

        echo json_encode($skills);
        die();
    }
}