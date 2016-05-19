<?php

namespace DSI\Controller;

use DSI\Entity\Project;
use DSI\Repository\ProjectRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;

class ProjectsController
{
    public $responseFormat = 'html';

    public function exec()
    {
        if ($this->responseFormat == 'json') {
            $projectRepo = new ProjectRepository();
            echo json_encode(array_map(function (Project $project) {
                $region = $project->getCountryRegion();
                return [
                    'id' => $project->getId(),
                    'name' => $project->getName(),
                    'region' => ($region ? $region->getName() : ''),
                    'country' => ($region ? $region->getCountry()->getName() : ''),
                ];
            }, $projectRepo->getAll()));
        } else {
            $authUser = new Auth();
            if ($authUser->getUserId())
                $loggedInUser = (new UserRepository())->getById($authUser->getUserId());
            else
                $loggedInUser = null;

            $data = [
                'loggedInUser' => $loggedInUser
            ];
            require __DIR__ . '/../../../www/projects.php';
        }
    }
}