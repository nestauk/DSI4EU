<?php

namespace Controllers\DsiIndex;

use DSI\Entity\User;
use DSI\Service\Auth;
use Models\Text;
use Services\Request;
use Services\Response;
use Services\URL;
use Services\View;

class DsiIndexController
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
        $this->loggedInUser = $this->authUser->getUserIfLoggedIn();
    }

    public function exec()
    {
        $title = Text::getByIdentifier('dsi-index-title');
        $description = Text::getByIdentifier('dsi-index-description');
        $content = Text::getByIdentifier('dsi-index-content');

        View::setPageTitle($title->getCopy() . ' - DSI4EU');
        View::setPageDescription($description->getCopy());
        return View::render(__DIR__ . '/../../Views/dsi-index.php', [
            'authUser' => $this->authUser,
            'loggedInUser' => $this->loggedInUser,
            'title' => $title,
            'content' => $content,
            'canEdit' => $this->canEdit(),
        ]);
    }

    /**
     * @return bool
     */
    private function canEdit(): bool
    {
        return $this->loggedInUser AND $this->loggedInUser->isEditorialAdmin();
    }

    public function edit()
    {
        if (Request::isGet())
            return $this->editGet();
        else if (Request::isPost())
            return $this->editPost();
        else
            return (new Response('Invalid header', Response::HTTP_FORBIDDEN))->send();
    }

    public function editGet()
    {
        return View::render(__DIR__ . '/../../Views/edit/dsi-index.php', [
            'authUser' => $this->authUser,
            'loggedInUser' => $this->loggedInUser,
            'title' => Text::getByIdentifier('dsi-index-title'),
            'description' => Text::getByIdentifier('dsi-index-description'),
            'content' => Text::getByIdentifier('dsi-index-content'),
        ]);
    }

    public function editPost()
    {
        $title = Text::getByIdentifier('dsi-index-title');
        $title->{Text::Copy} = $_POST['title'];
        $title->save();

        $description = Text::getByIdentifier('dsi-index-description');
        $description->{Text::Copy} = $_POST['description'];
        $description->save();

        $content = Text::getByIdentifier('dsi-index-content');
        $content->{Text::Copy} = $_POST['content'];
        $content->save();

        return true;
    }
}
