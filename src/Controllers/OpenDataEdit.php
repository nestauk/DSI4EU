<?php

namespace Controllers;

use DSI\Entity\User;
use DSI\Service\Auth;
use Models\Resource;
use Models\Text;
use Services\URL;
use Services\Request;
use Services\Response;
use Services\View;

class OpenDataEdit
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
        else if (Request::isPost())
            return $this->post();
        else
            return (new Response('Invalid header', Response::HTTP_FORBIDDEN))->send();
    }

    private function get()
    {
        return View::render(__DIR__ . '/../Views/edit/open-data.php', [
            'authUser' => $this->authUser,
            'loggedInUser' => $this->loggedInUser,
            'mainText' => Text::getByIdentifier('open-data-main-text'),
            'subText' => Text::getByIdentifier('open-data-sub-text'),
            'resources' => Resource::orderBy(Resource::Id, 'desc')->get(),
        ]);
    }

    private function post()
    {
        $mainText = Text::getByIdentifier('open-data-main-text');
        $mainText->{Text::Copy} = $_POST['main'];
        $mainText->save();

        $subText = Text::getByIdentifier('open-data-sub-text');
        $subText->{Text::Copy} = $_POST['sub'];
        $subText->save();

        return true;
    }
}