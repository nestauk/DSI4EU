<?php

namespace Controllers;

use DSI\Entity\Translation;
use DSI\Entity\User;
use DSI\NotFound;
use DSI\Service\Auth;
use DSI\Service\JsModules;
use Services\URL;
use Models\Relationship\ClusterLang;
use Services\Request;
use Services\Response;
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

    public function __construct($clusterID)
    {
        $this->clusterID = (int)$clusterID;
        $this->urlHandler = new URL();
        $this->authUser = new Auth();
        $this->loggedInUser = $this->authUser->getUserIfLoggedIn();

        /** @var ClusterLang $clusterLang */
        $this->clusterLang = ClusterLang::with('images')->where([
            [ClusterLang::ClusterID, $this->clusterID],
            // [ClusterLang::Lang, Translate::getCurrentLang()]
            [ClusterLang::Lang, Translation::DEFAULT_LANGUAGE]
        ])->first();

        if (!$this->clusterLang)
            throw new NotFound();
    }

    public function get()
    {
        if (!Request::isGet())
            return (new Response('Invalid header', Response::HTTP_FORBIDDEN))->send();

        return View::render(__DIR__ . '/../Views/clusters/cluster.php', [
            'authUser' => $this->authUser,
            'loggedInUser' => $this->loggedInUser,
            'cluster' => $this->clusterLang,
            'canEdit' => $this->canEdit(),
        ]);
    }

    public function edit()
    {
        if (!$this->canEdit())
            go_to($this->urlHandler->clusters());

        JsModules::setTinyMCE(true);
        $angularModules['fileUpload'] = true;
        return View::render(__DIR__ . '/../Views/clusters/cluster-edit.php', [
            'authUser' => $this->authUser,
            'loggedInUser' => $this->loggedInUser,
            'cluster' => $this->clusterLang,
            'canEdit' => $this->canEdit(),
            'angularModules' => ['fileUpload' => true],
        ]);
    }

    /**
     * @return bool
     */
    private function canEdit()
    {
        if ($this->loggedInUser AND $this->loggedInUser->isEditorialAdmin())
            return true;

        return false;
    }
}