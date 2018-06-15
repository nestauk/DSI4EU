<?php

namespace Controllers\API;

use DSI\Entity\Image;
use DSI\Entity\User;
use DSI\Service\Auth;
use DSI\Service\Translate;
use Models\Relationship\ClusterImg;
use Services\URL;
use Models\Relationship\ClusterLang;
use Services\Request;
use Services\Response;
use Services\JsonResponse;

class ClusterApiController
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

        if (!$this->clusterLang) {
            $this->clusterLang = new ClusterLang();
            $this->clusterLang->{ClusterLang::ClusterID} = $this->clusterID;
            $this->clusterLang->{ClusterLang::Lang} = Translate::getCurrentLang();
            $this->clusterLang->save();
        }

        if (Request::isGet())
            return $this->get();
        elseif (Request::isPost())
            return $this->save();
        else
            return (new Response('Invalid header', Response::HTTP_FORBIDDEN))->send();
    }

    private function save()
    {
        if (!$this->canEdit())
            return (new Response('Not allowed', Response::HTTP_UNAUTHORIZED))->send();

        $errors = [];
        if (trim($_POST['title']) === '')
            $errors['title'] = 'Please provide a cluster title';
        if ($errors)
            return (new JsonResponse($errors, Response::HTTP_FORBIDDEN))->send();

        $fields = [
            ClusterLang::Title,
            ClusterLang::Subtitle,
            ClusterLang::Paragraph,
            ClusterLang::Description,
            ClusterLang::GetInTouch,
        ];
        foreach ($fields AS $field)
            if (isset($_POST[$field])) $this->clusterLang->{$field} = $_POST[$field];

        $this->clusterLang->save();

        return $this->get();
    }

    private function get()
    {
        $this->clusterLang->images = $this->clusterLang->images->map(function (ClusterImg $clusterImg) {
            $clusterImg->path = Image::UPLOAD_FOLDER_URL . $clusterImg->filename;
        });
        return (new JsonResponse($this->clusterLang))->send();
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