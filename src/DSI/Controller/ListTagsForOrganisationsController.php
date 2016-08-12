<?php

namespace DSI\Controller;

use DSI\Repository\TagForOrganisationsRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class ListTagsForOrganisationsController
{
    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());

        $tagRepo = new TagForOrganisationsRepository();
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