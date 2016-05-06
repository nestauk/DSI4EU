<?php

namespace DSI\Controller;

use DSI\Repository\LanguageRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class ListLanguagesController
{
    public function exec()
    {
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo(URL::login());

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