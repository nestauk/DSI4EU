<?php

namespace DSI\Controller;

use DSI\Repository\TagForOrganisationsRepo;
use DSI\Service\Auth;
use Services\URL;

class ListTagsForOrganisationsController
{
    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());

        $tagRepo = new TagForOrganisationsRepo();
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