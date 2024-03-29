<?php

namespace Controllers\API;

use Actions\Uploads\MoveUploadedFromTemp;
use DSI\Entity\Image;
use DSI\Entity\User;
use DSI\NotFound;
use DSI\Service\Auth;
use Services\URL;
use Models\Relationship\ClusterImg;
use Models\Relationship\ClusterLang;
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

        if (Request::isGet())
            return $this->get();
        elseif (Request::isPost())
            return $this->save();
        elseif (Request::isDelete())
            return $this->delete();
        else
            return (new Response('Invalid method', Response::HTTP_FORBIDDEN))->send();
    }

    private function save()
    {
        if (!$this->clusterImgID)
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

        $exec = new MoveUploadedFromTemp();
        $exec->fileName = $_POST[ClusterImg::Filename];
        $exec->user = $this->loggedInUser;
        $exec->exec();

        $this->clusterImg = new ClusterImg();
        $this->clusterImg->{ClusterImg::ClusterLangID} = $clusterLang->{ClusterLang::Id};
        $this->clusterImg->{ClusterImg::Filename} = $exec->getNewFileName();
        $this->clusterImg->{ClusterImg::Caption} = $_POST[ClusterImg::Caption];
        $this->clusterImg->{ClusterImg::Link} = $_POST[ClusterImg::Link];
        $this->clusterImg->save();
        return $this->get();
    }

    private function update()
    {
        if (!$this->userCanMakeChanges()) return false;

        if (!$this->fetchClusterImage())
            return (new Response('Cluster Image not found', Response::HTTP_NOT_FOUND))->send();

        $fields = [
            ClusterImg::Caption,
            ClusterImg::Link,
        ];
        foreach ($fields AS $field)
            if (isset($_POST[$field])) $this->clusterImg->{$field} = $_POST[$field];

        $this->clusterImg->save();

        return $this->get();
    }

    private function get()
    {
        if (!$this->fetchClusterImage())
            return (new Response('Cluster Image not found', Response::HTTP_NOT_FOUND))->send();

        $this->clusterImg->path = Image::UPLOAD_FOLDER_URL . $this->clusterImg->filename;
        return (new JsonResponse($this->clusterImg))->send();
    }

    private function delete()
    {
        if (!$this->userCanMakeChanges()) return false;

        if (!$this->fetchClusterImage())
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

    private function fetchClusterImage()
    {
        if (!$this->clusterImg)
            $this->clusterImg = ClusterImg::find($this->clusterImgID);

        return $this->clusterImg;
    }
}