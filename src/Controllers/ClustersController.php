<?php

namespace Controllers;

use DSI\Entity\User;
use DSI\Service\Auth;
use DSI\Service\Translate;
use Services\URL;
use Models\ClusterLang;
use Services\View;

class ClustersController
{
    /** @var URL */
    private $urlHandler;

    /** @var Auth */
    private $authUser;

    /** @var User */
    private $loggedInUser;

    public function exec()
    {
        $this->urlHandler = new URL();
        $this->authUser = new Auth();
        $this->loggedInUser = $this->authUser->getUserIfLoggedIn();

        $clusters = ClusterLang
            ::with('images')
            ->where(ClusterLang::Lang, Translate::getCurrentLang())
            ->get();

        View::render(__DIR__ . '/../Views/clusters.php', [
            'urlHandler' => $this->urlHandler,
            'loggedInUser' => $this->loggedInUser,
            'clusters' => $clusters,
        ]);
    }
}