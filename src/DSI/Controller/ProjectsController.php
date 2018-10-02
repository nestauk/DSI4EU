<?php

namespace DSI\Controller;

use DSI\Entity\Project;
use DSI\Repository\ProjectDsiFocusTagRepo;
use DSI\Repository\ProjectImpactHelpTagRepo;
use DSI\Repository\ProjectImpactTechTagRepo;
use DSI\Repository\ProjectRepoInAPC;
use DSI\Repository\ProjectTagRepo;
use DSI\Service\Auth;
use Services\URL;

class ProjectsController
{
    public $responseFormat = 'html';

    /** @var ProjectDsiFocusTagRepo */
    private $projectDsiFocusTagRepo;

    /** @var ProjectTagRepo */
    private $projectTagRepo;

    /** @var ProjectImpactHelpTagRepo */
    private $projectImpactHelpTagRepo;

    /** @var ProjectImpactTechTagRepo */
    private $projectImpactTechTagRepo;

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();
        if ($this->responseFormat == 'json') {
            $projectRepositoryInAPC = new ProjectRepoInAPC();
            $this->projectDsiFocusTagRepo = new ProjectDsiFocusTagRepo();
            $this->projectTagRepo = new ProjectTagRepo();
            $this->projectImpactHelpTagRepo = new ProjectImpactHelpTagRepo();
            $this->projectImpactTechTagRepo = new ProjectImpactTechTagRepo();
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
            }, $projectRepositoryInAPC->getAllPublished()));
        } else {
            \Services\View::setPageTitle('Projects - DSI4EU');
            require __DIR__ . '/../../../www/views/projects.php';
        }
    }

    private function intval($int)
    {
        return (int)$int;
    }
}