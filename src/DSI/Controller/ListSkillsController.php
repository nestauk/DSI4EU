<?php

namespace DSI\Controller;

use DSI\Repository\SkillRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class ListSkillsController
{
    public function exec()
    {
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo(URL::login());

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