<?php

namespace DSI\Controller;

use DSI\Entity\Project;
use DSI\Repository\CountryRegionRepository;
use DSI\Repository\OrganisationProjectRepository;
use DSI\Repository\ProjectPostCommentRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class ProjectsController
{
    public $responseFormat = 'html';

    public function exec()
    {
        if ($this->responseFormat == 'json') {
            // (new CountryRegionRepository())->getAll();
            $projectRepo = new ProjectRepository();
            echo json_encode(array_map(function (Project $project) {
                $region = $project->getCountryRegion();
                return [
                    'id' => $project->getId(),
                    'name' => $project->getName(),
                    'region' => ($region ? $region->getName() : ''),
                    'country' => ($region ? $region->getCountry()->getName() : ''),
                    'url' => URL::project($project->getId(), $project->getName()),
                    'organisationsCount' => $project->getOrganisationsCount(),
                ];
            }, $projectRepo->getAll()));
        } else {
            $authUser = new Auth();
            if ($authUser->getUserId())
                $loggedInUser = (new UserRepository())->getById($authUser->getUserId());
            else
                $loggedInUser = null;

            $pageTitle = 'Projects';
            require __DIR__ . '/../../../www/projects.php';
        }
    }
}