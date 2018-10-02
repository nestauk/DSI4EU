<?php

namespace Controllers;

use DSI\Entity\User;
use DSI\Service\Auth;
use DSI\Service\Translate;
use Models\AuthorOfResource;
use Models\Cluster;
use Models\Relationship\ClusterLang;
use Models\Resource;
use Models\Text;
use Models\TypeOfResource;
use Services\URL;
use Services\View;
use DSI\Service\JsModules;

class OpenData
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
        $mainText = Text::getByIdentifier('open-data-main-text');
        $subText = Text::getByIdentifier('open-data-sub-text');

        JsModules::setMasonry(true);
        View::setPageTitle('Research and resources - DSI4EU');
        View::setPageDescription(__('Browse our curated library of research, publications, toolkits and resources related to digital social innovation and tech for good.'));
        return View::render(__DIR__ . '/../Views/open-data-research-and-resources.php', [
            'authUser' => $this->authUser,
            'loggedInUser' => $this->loggedInUser,
            'mainText' => $mainText,
            'subText' => $subText,
            'canEdit' => $this->canEdit(),
            'authors' => AuthorOfResource::all(),
            'clusters' => Cluster
                ::with(['clusterLangs' => function ($query) {
                    $query->where(ClusterLang::Lang, Translate::getCurrentLang());
                }])
                ->get(),
            'types' => TypeOfResource::all(),
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