<?php

namespace DSI\Controller;

use DSI\Repository\LanguageRepo;
use DSI\Service\Auth;
use DSI\Service\URL;

class ListLanguagesController
{
    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());

        $langRepo = new LanguageRepo();
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