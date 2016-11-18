<?php

namespace DSI\Controller;

use DSI\Entity\Organisation;
use DSI\Repository\CountryRegionRepository;
use DSI\Repository\OrganisationNetworkTagRepository;
use DSI\Repository\OrganisationRepositoryInAPC;
use DSI\Repository\OrganisationTagRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class OrganisationsController
{
    public $responseFormat = 'html';

    /** @var OrganisationTagRepository */
    private $organisationTagRepo;

    /** @var OrganisationNetworkTagRepository */
    private $organisationNetworkTagRepo;

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();

        $this->organisationTagRepo = new OrganisationTagRepository();
        $this->organisationNetworkTagRepo = new OrganisationNetworkTagRepository();

        if ($this->responseFormat == 'json') {
            // (new CountryRepository())->getAll();
            (new CountryRegionRepository())->getAll();
            // (new OrganisationTypeRepository())->getAll();
            // (new OrganisationSizeRepository())->getAll();

            $organisationRepositoryInAPC = new OrganisationRepositoryInAPC();
            echo json_encode(array_map(function (Organisation $organisation) use ($urlHandler) {
                return [
                    'id' => $organisation->getId(),
                    'name' => $organisation->getName(),
                    'region' => $organisation->getRegionName(),
                    'country' => $organisation->getCountryName(),
                    'countryID' => $organisation->getCountryID(),
                    'url' => $urlHandler->organisation($organisation),
                    'logo' => $organisation->getLogoOrDefaultSilver(),
                    'projectsCount' => $organisation->getProjectsCount(),
                    'partnersCount' => $organisation->getPartnersCount(),
                    'tags' => array_map('intval', $this->organisationTagRepo->getTagIDsForOrganisation($organisation)),
                    'netwTags' => array_map('intval', $this->organisationNetworkTagRepo->getTagIDsForOrganisation($organisation)),
                ];
            }, $organisationRepositoryInAPC->getAll()));
        } else {
            $pageTitle = 'Organisations';
            require __DIR__ . '/../../../www/views/organisations.php';
        }
    }
}