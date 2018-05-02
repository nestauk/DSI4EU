<?php

namespace Controllers\API;

use DSI\Entity\User;
use DSI\NotFound;
use DSI\Service\Auth;
use Services\URL;
use Models\ClusterImg;
use Models\ClusterLang;
use Services\Request;
use Services\Response;
use Services\JsonResponse;

class ClusterImgApiController
{
    /** @var URL */
    private $urlHandler;

    /** @var int */
    public $clusterImgID;

    /** @var ClusterImg */
    private $clusterImg;

    /** @var User */
    private $loggedInUser;

    /** @var Auth */
    private $authUser;

    public function exec()
    {
        $this->urlHandler = new URL();
        $this->authUser = new Auth();
        $this->loggedInUser = $this->authUser->getUserIfLoggedIn();

        if ($this->clusterImgID) {
            $this->clusterImg = ClusterImg::find($this->clusterImgID);
            if (!$this->clusterImg) {
                (new Response('Cluster Image not found', Response::HTTP_NOT_FOUND))->send();
                throw new NotFound();
            }
        }

        if (Request::isMethod(Request::METHOD_GET))
            return $this->get();
        elseif (Request::isMethod(Request::METHOD_POST))
            return $this->save();
        elseif (Request::isMethod(Request::METHOD_DELETE))
            return $this->delete();
        else
            return (new Response('Invalid header', Response::HTTP_FORBIDDEN))->send();
    }

    private function save()
    {
        if (!$this->clusterImg)
            return $this->insert();
        else
            return $this->update();
    }

    private function insert()
    {
        if (!$this->userCanMakeChanges()) return false;

        $clusterLang = ClusterLang::find($_POST[ClusterImg::ClusterLangID]);
        if (!$clusterLang)
            return (new Response('Cluster Lang not found', Response::HTTP_NOT_FOUND))->send();

        $this->clusterImg = new ClusterImg();
        $this->clusterImg->{ClusterImg::ClusterLangID} = $clusterLang->{ClusterLang::Id};
        $this->clusterImg->{ClusterImg::Filename} = $_POST[ClusterImg::Filename];
        $this->clusterImg->{ClusterImg::Link} = $_POST[ClusterImg::Link];
        $this->clusterImg->save();
        return $this->get();
    }

    private function update()
    {
        if (!$this->userCanMakeChanges()) return false;

        if (!$this->clusterImg)
            return (new Response('Cluster Image not found', Response::HTTP_NOT_FOUND))->send();

        $fields = [
            ClusterImg::Link,
        ];
        foreach ($fields AS $field)
            if (isset($_POST[$field])) $this->clusterImg->{$field} = $_POST[$field];

        $this->clusterImg->save();

        return $this->get();
    }

    private function get()
    {
        if (!$this->clusterImg)
            return (new Response('Cluster Image not found', Response::HTTP_NOT_FOUND))->send();

        return (new JsonResponse($this->clusterImg))->send();
    }

    private function delete()
    {
        if (!$this->userCanMakeChanges()) return false;

        if (!$this->clusterImg)
            return (new Response('Cluster Image not found', Response::HTTP_NOT_FOUND))->send();

        return $this->clusterImg->delete();
    }

    private function userCanMakeChanges()
    {
        if (!$this->authUser->isLoggedIn()) {
            (new Response('Not logged in', Response::HTTP_UNAUTHORIZED))->send();
            return false;
        }

        if (!$this->loggedInUser->isEditorialAdmin()) {
            (new Response('Not admin', Response::HTTP_UNAUTHORIZED))->send();
            return false;
        }

        return true;
    }
}