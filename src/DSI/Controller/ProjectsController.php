<?php

namespace DSI\Controller;

use DSI\Entity\Project;
use DSI\Repository\ProjectRepositoryInAPC;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class ProjectsController
{
    public $responseFormat = 'html';

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();

        if ($this->responseFormat == 'json') {
            // (new CountryRegionRepository())->getAll();
            $projectRepositoryInAPC = new ProjectRepositoryInAPC();
            echo json_encode(array_map(function (Project $project) use ($urlHandler) {
                $region = $project->getRegion();
                return [
                    'id' => $project->getId(),
                    'name' => $project->getName(),
                    'region' => ($region ? $region->getName() : ''),
                    'country' => ($region ? $region->getCountry()->getName() : ''),
                    'url' => $urlHandler->project($project),
                    'logo' => $project->getLogoOrDefaultSilver(),
                    'organisationsCount' => $project->getOrganisationsCount(),
                ];
            }, $projectRepositoryInAPC->getAll()));
        } else {
            $pageTitle = 'Projects';
            require __DIR__ . '/../../../www/views/projects.php';
        }
    }
}