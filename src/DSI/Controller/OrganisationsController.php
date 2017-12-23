<?php

namespace DSI\Controller;

use DSI\Entity\Organisation;
use DSI\Repository\CountryRegionRepo;
use DSI\Repository\OrganisationNetworkTagRepo;
use DSI\Repository\OrganisationRepoInAPC;
use DSI\Repository\OrganisationTagRepo;
use DSI\Service\Auth;
use DSI\Service\URL;

class OrganisationsController
{
    public $responseFormat = 'html';

    /** @var OrganisationTagRepo */
    private $organisationTagRepo;

    /** @var OrganisationNetworkTagRepo */
    private $organisationNetworkTagRepo;

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();

        $this->organisationTagRepo = new OrganisationTagRepo();
        $this->organisationNetworkTagRepo = new OrganisationNetworkTagRepo();

        if ($this->responseFormat == 'json') {
            // (new CountryRepository())->getAll();
            (new CountryRegionRepo())->getAll();
            // (new OrganisationTypeRepository())->getAll();
            // (new OrganisationSizeRepository())->getAll();

            $organisationRepositoryInAPC = new OrganisationRepoInAPC();
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