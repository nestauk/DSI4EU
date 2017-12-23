<?php

namespace DSI\Controller;

use DSI\Repository\ImpactTagRepo;
use DSI\Service\Auth;
use DSI\Service\URL;

class ListImpactTagsController
{
    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());

        $tagRepo = new ImpactTagRepo();
        $tags = [];
        foreach($tagRepo->getAll() AS $tag){
            $tags[] = [
                'id' => $tag->getName(),
                'text' => $tag->getName(),
            ];
        }

        echo json_encode($tags);
        die();
    }
}