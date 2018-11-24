<?php

namespace Controllers\DsiIndex;

use DSI\Entity\User;
use DSI\Service\Auth;
use Models\Text;
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
}