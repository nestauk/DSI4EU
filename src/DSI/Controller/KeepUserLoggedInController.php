<?php

namespace DSI\Controller;

use DSI\Service\Auth;
use Services\URL;
use DSI\UseCase\RememberPermanentLogin;

class KeepUserLoggedInController
{
    public function exec()
    {
        $urlHandler = new URL();
        $auth = new Auth();
        $auth->ifNotLoggedInRedirectTo($urlHandler->login());

        $action = new RememberPermanentLogin();
        $action->setUser($auth->getUser());
        $action->exec();

        echo json_encode([
            'code' => 'ok',
            'message' => __('You will automatically be logged in next time when you visit the website.'),
        ]);
    }
}