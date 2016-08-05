<?php

namespace DSI\Controller;

use DSI\Entity\Organisation;
use DSI\Repository\CountryRegionRepository;
use DSI\Repository\CountryRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\OrganisationRepositoryInAPC;
use DSI\Repository\OrganisationSizeRepository;
use DSI\Repository\OrganisationTypeRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class OrganisationsController
{
    public $responseFormat = 'html';

    public function exec()
    {
        $authUser = new Auth();
        if ($authUser->isLoggedIn())
            $loggedInUser = (new UserRepository())->getById($authUser->getUserId());
        else
            $loggedInUser = null;

        if ($this->responseFormat == 'json') {
            // (new CountryRepository())->getAll();
            (new CountryRegionRepository())->getAll();
            // (new OrganisationTypeRepository())->getAll();
            // (new OrganisationSizeRepository())->getAll();

            $organisationRepositoryInAPC = new OrganisationRepositoryInAPC();
            echo json_encode(array_map(function (Organisation $organisation) {
                $region = $organisation->getCountryRegion();
                return [
                    'id' => $organisation->getId(),
                    'name' => $organisation->getName(),
                    'region' => ($region ? $region->getName() : ''),
                    'country' => ($region ? $region->getCountry()->getName() : ''),
                    'url' => URL::organisation($organisation),
                    'projectsCount' => $organisation->getProjectsCount(),
                    'partnersCount' => $organisation->getPartnersCount(),
                ];
            }, $organisationRepositoryInAPC->getAll()));
        } else {
            $pageTitle = 'Organisations';
            require __DIR__ . '/../../../www/organisations.php';
        }
    }
}