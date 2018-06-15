<?php

namespace Controllers\API;

use Actions\OpenResources\OpenResourceCreate;
use Actions\OpenResources\OpenResourceEdit;
use DSI\Entity\Image;
use DSI\Entity\User;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use Models\Relationship\ResourceCluster;
use Models\Resource;
use Services\URL;
use Services\Request;
use Services\Response;
use Services\JsonResponse;

class OpenResourceApiController
{
    /** @var URL */
    private $urlHandler;

    /** @var Resource */
    private $resource;

    /** @var User */
    private $loggedInUser;

    /** @var Auth */
    private $authUser;

    public function __construct()
    {
        $this->urlHandler = new URL();
        $this->authUser = new Auth();
        $this->loggedInUser = $this->authUser->getUserIfLoggedIn();
    }

    public function createObject()
    {
        if (!Request::isPost())
            return (new Response('Invalid header', Response::HTTP_FORBIDDEN))->send();

        if (!$this->canEdit())
            return (new Response('Not allowed', Response::HTTP_UNAUTHORIZED))->send();

        try {
            $exec = new OpenResourceCreate();
            $exec->executor = $this->loggedInUser;
            $exec->title = $_POST[Resource::Title];
            $exec->description = $_POST[Resource::Description];
            $exec->linkText = $_POST[Resource::LinkText];
            $exec->linkUrl = $_POST[Resource::LinkUrl];
            $exec->image = $_POST[Resource::Image];
            $exec->clusters = $_POST[Resource::Clusters];
            $exec->authorID = $_POST[Resource::AuthorID];

            $exec->exec();
            return (new JsonResponse([
                'url' => $this->urlHandler->openDataResearchAndResourcesEdit()
            ], Response::HTTP_OK))->send();
        } catch (ErrorHandler $e) {
            return (new JsonResponse($e->getErrors(), Response::HTTP_FORBIDDEN))->send();
        }
    }

    public function edit($resourceID)
    {
        $this->resource = Resource::find($resourceID);
        if (!$this->resource)
            return (new Response('Not found', Response::HTTP_NOT_FOUND))->send();

        if (Request::isGet())
            return $this->getObject();
        elseif (Request::isPost())
            return $this->updateObject();
        elseif (Request::isDelete())
            return $this->deleteObject();
        else
            return (new Response('Invalid header', Response::HTTP_FORBIDDEN))->send();
    }

    private function updateObject()
    {
        if (!$this->canEdit())
            return (new Response('Not allowed', Response::HTTP_UNAUTHORIZED))->send();

        try {
            $exec = new OpenResourceEdit();
            $exec->executor = $this->loggedInUser;
            $exec->resource = $this->resource;

            $exec->title = $_POST[Resource::Title];
            $exec->description = $_POST[Resource::Description];
            $exec->linkText = $_POST[Resource::LinkText];
            $exec->linkUrl = $_POST[Resource::LinkUrl];
            $exec->clusters = $_POST[Resource::Clusters];
            $exec->authorID = $_POST[Resource::AuthorID];

            if ($_POST[Resource::Image])
                $exec->image = $_POST[Resource::Image];
            $exec->exec();

            return (new JsonResponse([], Response::HTTP_OK))->send();
        } catch (ErrorHandler $e) {
            return (new JsonResponse($e->getErrors(), Response::HTTP_FORBIDDEN))->send();
        }
    }

    private function deleteObject()
    {
        if (!$this->canEdit())
            return (new Response('Not allowed', Response::HTTP_UNAUTHORIZED))->send();

        try {
            $this->resource->delete();

            ResourceCluster
                ::where(ResourceCluster::ResourceID, $this->resource->getId())
                ->delete();

            return (new JsonResponse([], Response::HTTP_OK))->send();
        } catch (ErrorHandler $e) {
            return (new JsonResponse($e->getErrors(), Response::HTTP_FORBIDDEN))->send();
        }
    }

    private function getObject()
    {
        $this->resource->{Resource::Image} =
            $this->resource->{Resource::Image} ?
                Image::UPLOAD_FOLDER_URL . $this->resource->{Resource::Image} :
                '';
        
        $clusters = new \stdClass();
        ResourceCluster
            ::where(ResourceCluster::ResourceID, $this->resource->getId())
            ->get()
            ->map(function (ResourceCluster $resourceCluster) use ($clusters) {
                $clusters->{$resourceCluster->{ResourceCluster::ClusterID}} = 1;
            });

        $this->resource->{Resource::Clusters} = (array)$clusters;

        return (new JsonResponse($this->resource))->send();
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