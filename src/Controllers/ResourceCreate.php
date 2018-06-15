<?php

namespace Controllers;

use DSI\Entity\User;
use DSI\Service\Auth;
use Models\AuthorOfResource;
use Models\Cluster;
use Models\Relationship\ClusterLang;
use Models\Text;
use Models\TypeOfResource;
use Services\URL;
use Services\Request;
use Services\Response;
use Services\View;

class ResourceCreate
{
    /** @var URL */
    private $urlHandler;

    /** @var User */
    private $loggedInUser;

    /** @var Auth */
    private $authUser;

    public function __construct()
    {
        $this->urlHandler = new URL();
        $this->authUser = new Auth();
        $this->authUser->ifNotLoggedInRedirectTo($this->urlHandler->login());
        $this->loggedInUser = $this->authUser->getUser();

        if (!$this->loggedInUser->isEditorialAdmin())
            go_to($this->urlHandler->dashboard());
    }

    public function exec()
    {
        if (Request::isGet())
            return $this->get();
        else
            return (new Response('Invalid header', Response::HTTP_FORBIDDEN))->send();
    }

    private function get()
    {
        return View::render(__DIR__ . '/../Views/resource-create.php', [
            'loggedInUser' => $this->loggedInUser,
            'clusters' => Cluster
                ::with(['clusterLangs' => function ($query) {
                    $query->where(ClusterLang::Lang, 'en');
                }])
                ->get(),
            'authors' => AuthorOfResource::all(),
            'types' => TypeOfResource::all(),
        ]);
    }
}