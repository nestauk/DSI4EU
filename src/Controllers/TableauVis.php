<?php

namespace Controllers;

use DSI\Service\Auth;
use Services\View;

class TableauVis
{
    public function exec()
    {
        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();

        View::setPageTitle('DSI Visualisation');
        View::setPageDescription(__('Supporting Digital Social Innovation (DSI), tech for good and civic tech through research, policy and practical support.'));
        return View::render(__DIR__ . '/../Views/tableau-vis.php', [
            'loggedInUser' => $loggedInUser
        ]);
    }
}
