<?php

namespace DSI\Controller;

use DSI\Repository\TagForProjectsRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class ListTagsForProjectsController
{
    public function exec()
    {
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo(URL::login());

        $tagRepo = new TagForProjectsRepository();
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