<?php

namespace DSI\Controller;

use DSI\Entity\ImpactTag;
use DSI\Entity\NetworkTag;
use DSI\Entity\TagForOrganisations;
use DSI\Entity\TagForProjects;
use DSI\Entity\User;
use DSI\Repository\ImpactTagRepo;
use DSI\Repository\NetworkTagRepo;
use DSI\Repository\OrganisationNetworkTagRepo;
use DSI\Repository\OrganisationTagRepo;
use DSI\Repository\ProjectDsiFocusTagRepo;
use DSI\Repository\ProjectImpactHelpTagRepo;
use DSI\Repository\ProjectImpactTechTagRepo;
use DSI\Repository\ProjectNetworkTagRepo;
use DSI\Repository\ProjectTagRepo;
use DSI\Repository\TagForOrganisationsRepo;
use DSI\Repository\TagForProjectsRepo;
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

        if (isset($_POST['deleteNetworkTag']))
            return $this->deleteNetworkTag($_POST['tagID']);

        if (isset($_POST['deleteOrganisationTag']))
            return $this->deleteOrganisationTag($_POST['tagID']);

        if (isset($_POST['deleteProjectTag']))
            return $this->deleteProjectTag($_POST['tagID']);

        if (isset($_POST['deleteProjectImpactTag']))
            return $this->deleteProjectImpactTag($_POST['tagID']);

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

        return true;
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
        }, (new NetworkTagRepo())->getAll());
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
        }, (new TagForOrganisationsRepo())->getAll());
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
        }, (new TagForProjectsRepo())->getAll());
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
        }, (new ImpactTagRepo())->getAll());
    }

    /**
     * @param User $loggedInUser
     * @return bool
     */
    private function canUserManageTags(User $loggedInUser): bool
    {
        return $loggedInUser->isCommunityAdmin();
    }

    /**
     * @param $tagID
     */
    private function removeOrganisationNetworkTags($tagID)
    {
        try {
            $organisationNetworkTagRepository = new OrganisationNetworkTagRepo();
            $organisationTags = $organisationNetworkTagRepository->getByTagID($tagID);
            foreach ($organisationTags AS $organisationTag) {

                $organisationNetworkTagRepository->remove($organisationTag);
            }
        } catch (\Exception $e) {
        }
    }

    /**
     * @param $tagID
     */
    private function removeProjectNetworkTags($tagID)
    {
        try {
            $projectNetworkTagRepository = new ProjectNetworkTagRepo();
            $projectTags = $projectNetworkTagRepository->getByTagID($tagID);
            foreach ($projectTags AS $projectTag) {
                $projectNetworkTagRepository->remove($projectTag);
            }
        } catch (\Exception $e) {
        }
    }

    /**
     * @param $tagID
     */
    private function removeNetworkTag($tagID)
    {
        $networkTagRepository = new NetworkTagRepo();
        $tag = $networkTagRepository->getById($tagID);
        $networkTagRepository->remove($tag);
    }

    /**
     * @param $tagID
     * @return bool
     */
    private function deleteNetworkTag($tagID): bool
    {
        $this->removeOrganisationNetworkTags($tagID);
        $this->removeProjectNetworkTags($tagID);
        $this->removeNetworkTag($tagID);

        echo json_encode([
            'code' => 'ok'
        ]);

        return true;
    }

    /**
     * @param $tagID
     * @return bool
     */
    private function deleteOrganisationTag($tagID): bool
    {
        $this->removeOrganisationTags($tagID);
        $this->removeTagForOrganisation($tagID);

        echo json_encode([
            'code' => 'ok'
        ]);

        return true;
    }

    /**
     * @param $tagID
     * @return bool
     */
    private function deleteProjectTag($tagID): bool
    {
        $this->removeProjectTags($tagID);
        $this->removeTagForProject($tagID);

        echo json_encode([
            'code' => 'ok'
        ]);

        return true;
    }

    /**
     * @param $tagID
     * @return bool
     */
    private function deleteProjectImpactTag($tagID): bool
    {
        $this->removeProjectImpactTechTags($tagID);
        $this->removeProjectImpactHelpTags($tagID);
        $this->removeImpactTag($tagID);

        echo json_encode([
            'code' => 'ok'
        ]);

        return true;
    }

    /**
     * @param $tagID
     */
    private function removeOrganisationTags($tagID)
    {
        $organisationTagRepository = new OrganisationTagRepo();
        $organisationTags = $organisationTagRepository->getByTagID($tagID);
        foreach ($organisationTags AS $organisationTag) {
            $organisationTagRepository->remove($organisationTag);
        }
    }

    /**
     * @param $tagID
     */
    private function removeTagForOrganisation($tagID)
    {
        $tagForOrganisationsRepository = new TagForOrganisationsRepo;
        $tag = $tagForOrganisationsRepository->getById($tagID);
        $tagForOrganisationsRepository->remove($tag);
    }

    /**
     * @param $tagID
     */
    private function removeProjectTags($tagID)
    {
        $projectTagRepository = new ProjectTagRepo();
        $projectTags = $projectTagRepository->getByTagID($tagID);
        foreach ($projectTags AS $projectTag) {
            $projectTagRepository->remove($projectTag);
        }
    }

    /**
     * @param $tagID
     */
    private function removeTagForProject($tagID)
    {
        $tagForProjectsRepository = new TagForProjectsRepo();
        $tag = $tagForProjectsRepository->getById($tagID);
        $tagForProjectsRepository->remove($tag);
    }

    /**
     * @param $tagID
     */
    private function removeProjectImpactTechTags($tagID)
    {
        $projectImpactTechTagRepository = new ProjectImpactTechTagRepo();
        $projectImpactTechTags = $projectImpactTechTagRepository->getByTagID($tagID);
        foreach ($projectImpactTechTags AS $projectImpactTechTag) {
            $projectImpactTechTagRepository->remove($projectImpactTechTag);
        }
    }

    /**
     * @param $tagID
     */
    private function removeProjectImpactHelpTags($tagID)
    {
        $projectImpactHelpTagRepository = new ProjectImpactHelpTagRepo();
        $projectImpactHelpTags = $projectImpactHelpTagRepository->getByTagID($tagID);
        foreach ($projectImpactHelpTags AS $projectImpactHelpTag) {
            $projectImpactHelpTagRepository->remove($projectImpactHelpTag);
        }
    }

    /**
     * @param $tagID
     */
    private function removeImpactTag($tagID)
    {
        $impactTagRepository = new ImpactTagRepo();
        $impactTag = $impactTagRepository->getById($tagID);
        $impactTagRepository->remove($impactTag);
    }
}