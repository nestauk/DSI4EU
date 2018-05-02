<?php

namespace Controllers;

use DSI\Entity\User;
use DSI\Service\Auth;
use DSI\Service\Translate;
use Services\URL;
use Models\ClusterLang;
use Services\Request;
use Services\Response;
use Services\JsonResponse;
use Services\View;

class ClusterController
{
    /** @var URL */
    private $urlHandler;

    /** @var int */
    public $clusterID;

    /** @var ClusterLang */
    private $clusterLang;

    /** @var User */
    private $loggedInUser;

    /** @var Auth */
    private $authUser;

    public function exec()
    {
        $this->urlHandler = new URL();
        $this->authUser = new Auth();
        $this->loggedInUser = $this->authUser->getUserIfLoggedIn();

        /** @var ClusterLang $clusterLang */
        $this->clusterLang = ClusterLang::with('images')->where([
            [ClusterLang::ClusterID, $this->clusterID],
            [ClusterLang::Lang, Translate::getCurrentLang()]
        ])->first();

        if (!$this->clusterLang)
            return View::render(__DIR__ . '/../Views/404-not-found.php');

        if (Request::isMethod(Request::METHOD_GET))
            return $this->get();
        else
            return (new Response('Invalid header', Response::HTTP_FORBIDDEN))->send();
    }

    private function get()
    {
        return View::render(__DIR__ . '/../Views/cluster.php', [
            'authUser' => $this->authUser,
            'loggedInUser' => $this->loggedInUser,
            'cluster' => $this->clusterLang,
        ]);
    }
}