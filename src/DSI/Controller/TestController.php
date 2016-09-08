<?php
namespace DSI\Controller;

use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\Translate;

class TestController
{
    public $format = 'html';

    public function exec()
    {
        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();

        pr(Translate::getTranslationFor('en', 'Dashboard'));
        pr(Translate::getTranslationFor('de', 'Dashboard'));
        pr(Translate::getTranslationFor('fr', 'Dashboard'));
        pr([
            Translate::getCurrentLang(),
            Translate::getTranslation('Dashboard'),
        ]);
        pr([
            Translate::getCurrentLang(),
            Translate::getTranslation('Events'),
        ]);
        pr([
            Translate::getCurrentLang(),
            Translate::getTranslation('Projects'),
        ]);
    }
}