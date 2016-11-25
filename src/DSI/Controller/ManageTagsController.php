<?php

namespace DSI\Controller;

use DSI\Entity\ImpactTag;
use DSI\Entity\NetworkTag;
use DSI\Entity\TagForOrganisations;
use DSI\Entity\TagForProjects;
use DSI\Entity\User;
use DSI\Repository\ImpactTagRepository;
use DSI\Repository\NetworkTagRepository;
use DSI\Repository\TagForOrganisationsRepository;
use DSI\Repository\TagForProjectsRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class ManageTagsController
{
    public $responseFormat = 'html';

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());
        $loggedInUser = $authUser->getUser();

        $userCanManageTags = $this->canUserManageTags($loggedInUser);
        if (!$userCanManageTags)
            go_to($urlHandler->home());

        if ($this->responseFormat == 'json') {
            echo json_encode([
                'networkTags' => $this->getNetworkTags(),
                'organisationTags' => $this->getOrganisationTags(),
                'projectTags' => $this->getProjectTags(),
                'projectImpactTags' => $this->getProjectImpactTags(),
            ]);
        } else {
            $pageTitle = 'Manage Tags';
            require __DIR__ . '/../../../www/views/manage-tags.php';
        }
    }

    /**
     * @return array
     */
    private function getNetworkTags()
    {
        return array_map(function (NetworkTag $tag) {
            return [
                'id' => $tag->getId(),
                'name' => $tag->getName(),
            ];
        }, (new NetworkTagRepository())->getAll());
    }

    /**
     * @return array
     */
    private function getOrganisationTags()
    {
        return array_map(function (TagForOrganisations $tag) {
            return [
                'id' => $tag->getId(),
                'name' => $tag->getName(),
            ];
        }, (new TagForOrganisationsRepository())->getAll());
    }

    /**
     * @return array
     */
    private function getProjectTags()
    {
        return array_map(function (TagForProjects $tag) {
            return [
                'id' => $tag->getId(),
                'name' => $tag->getName(),
            ];
        }, (new TagForProjectsRepository())->getAll());
    }

    /**
     * @return array
     */
    private function getProjectImpactTags()
    {
        return array_map(function (ImpactTag $tag) {
            return [
                'id' => $tag->getId(),
                'name' => $tag->getName(),
            ];
        }, (new ImpactTagRepository())->getAll());
    }

    /**
     * @param User $loggedInUser
     * @return bool
     */
    private function canUserManageTags(User $loggedInUser):bool
    {
        return $loggedInUser->isCommunityAdmin();
    }
}