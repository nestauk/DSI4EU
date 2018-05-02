<?php

namespace Controllers\API;

use DSI\Entity\User;
use DSI\Service\Auth;
use DSI\Service\Translate;
use Services\URL;
use Models\ClusterLang;
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

        if (Request::isMethod(Request::METHOD_GET))
            return $this->get();
        elseif (Request::isMethod(Request::METHOD_POST))
            return $this->save();
        else
            return (new Response('Invalid header', Response::HTTP_FORBIDDEN))->send();
    }

    private function save()
    {
        if (!$this->authUser->isLoggedIn())
            return (new Response('Not logged in', Response::HTTP_UNAUTHORIZED))->send();

        if (!$this->loggedInUser->isEditorialAdmin())
            return (new Response('Not admin', Response::HTTP_UNAUTHORIZED))->send();

        $errors = [];
        if (trim($_POST['title']) === '')
            $errors['title'] = 'Please provide a cluster title';
        if ($errors)
            return (new JsonResponse($errors, Response::HTTP_FORBIDDEN))->send();

        $fields = [
            ClusterLang::Title,
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
        return (new JsonResponse($this->clusterLang))->send();
    }
}