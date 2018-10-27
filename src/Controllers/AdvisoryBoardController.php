<?php

namespace Controllers;

use DSI\Entity\User;
use DSI\Service\Auth;
use Services\View;

class AdvisoryBoardController
{
    /** @var int */
    public $clusterID;

    /** @var User */
    private $loggedInUser;

    /** @var Auth */
    private $authUser;

    public function exec()
    {
        $this->authUser = new Auth();
        $this->loggedInUser = $this->authUser->getUserIfLoggedIn();

        View::setPageTitle('Advisory Board - DSI4EU');
        View::setPageDescription(__('Find out more about the DSI4EU Advisory Board of experts from across Europe.'));
        return View::render(__DIR__ . '/../Views/advisory-board.php', [
            'loggedInUser' => $this->loggedInUser,
        ]);
    }
}