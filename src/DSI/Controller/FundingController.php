<?php

namespace DSI\Controller;

use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class FundingController
{
    public $format = 'html';

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        if ($authUser->getUserId())
            $loggedInUser = (new UserRepository())->getById($authUser->getUserId());
        else
            $loggedInUser = null;

        if ($this->format == 'json') {
            /*
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
            */
        } else {
            $pageTitle = 'Funding Opportunities';
            require __DIR__ . '/../../../www/funding.php';
        }
    }
}