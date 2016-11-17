<?php

namespace DSI\Controller;

use DSI\Entity\Project;
use DSI\Repository\ProjectDsiFocusTagRepository;
use DSI\Repository\ProjectImpactHelpTagRepository;
use DSI\Repository\ProjectImpactTechTagRepository;
use DSI\Repository\ProjectRepositoryInAPC;
use DSI\Repository\ProjectTagRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class ProjectsController
{
    public $responseFormat = 'html';

    /** @var ProjectDsiFocusTagRepository */
    private $projectDsiFocusTagRepo;

    /** @var ProjectTagRepository */
    private $projectTagRepo;

    /** @var ProjectImpactHelpTagRepository */
    private $projectImpactHelpTagRepo;

    /** @var ProjectImpactTechTagRepository */
    private $projectImpactTechTagRepo;

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();
        if ($this->responseFormat == 'json') {
            $projectRepositoryInAPC = new ProjectRepositoryInAPC();
            $this->projectDsiFocusTagRepo = new ProjectDsiFocusTagRepository();
            $this->projectTagRepo = new ProjectTagRepository();
            $this->projectImpactHelpTagRepo = new ProjectImpactHelpTagRepository();
            $this->projectImpactTechTagRepo = new ProjectImpactTechTagRepository();
            echo json_encode(array_map(function (Project $project) use ($urlHandler) {
                return [
                    'id' => $project->getId(),
                    'name' => $project->getName(),
                    'region' => $project->getRegionName(),
                    'country' => $project->getCountryName(),
                    'countryID' => $project->getCountryID(),
                    'url' => $urlHandler->project($project),
                    'logo' => $project->getLogoOrDefaultSilver(),
                    'organisationsCount' => $project->getOrganisationsCount(),
                    'dsiFocusTags' => array_map('intval', $this->projectDsiFocusTagRepo->getTagIDsByProject($project)),
                    'tags' => array_map('intval', $this->projectTagRepo->getTagIDsByProject($project)),
                    'helpTags' => array_map('intval', $this->projectImpactHelpTagRepo->getTagIDsByProject($project)),
                    'techTags' => array_map('intval', $this->projectImpactTechTagRepo->getTagIDsByProject($project)),
                ];
            }, $projectRepositoryInAPC->getAll()));
        } else {
            $pageTitle = 'Projects';
            require __DIR__ . '/../../../www/views/projects.php';
        }
    }

    private function intval($int)
    {
        return (int)$int;
    }
}