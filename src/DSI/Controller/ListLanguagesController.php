<?php

namespace DSI\Controller;

use DSI\Repository\LanguageRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class ListLanguagesController
{
    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());

        $langRepo = new LanguageRepository();
        $languages = [];
        foreach($langRepo->getAll() AS $lang){
            $languages[] = [
                'id' => $lang->getName(),
                'text' => $lang->getName(),
            ];
        }

        echo json_encode($languages);
        die();
    }
}