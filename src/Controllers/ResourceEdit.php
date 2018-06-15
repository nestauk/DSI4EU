<?php

namespace Controllers;

use DSI\Entity\User;
use DSI\Service\Auth;
use Models\AuthorOfResource;
use Models\Cluster;
use Models\Relationship\ClusterLang;
use Models\Resource;
use Models\TypeOfResource;
use Services\URL;
use Services\Request;
use Services\Response;
use Services\View;

class ResourceEdit
{
    /** @var URL */
    private $urlHandler;

    /** @var User */
    private $loggedInUser;

    /** @var Auth */
    private $authUser;

    /** @var Resource */
    public $resource;

    public function __construct($resourceID)
    {
        $this->urlHandler = new URL();
        $this->authUser = new Auth();
        $this->authUser->ifNotLoggedInRedirectTo($this->urlHandler->login());
        $this->loggedInUser = $this->authUser->getUser();

        if (!$this->loggedInUser->isEditorialAdmin())
            go_to($this->urlHandler->dashboard());

        $this->resource = Resource::find($resourceID);
        if (!$this->resource)
            go_to($this->urlHandler->openDataResearchAndResourcesEdit());
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
        return View::render(__DIR__ . '/../Views/resource-edit.php', [
            'loggedInUser' => $this->loggedInUser,
            'resource' => $this->resource,
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