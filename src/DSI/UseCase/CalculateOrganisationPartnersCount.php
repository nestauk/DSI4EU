<?php

namespace DSI\UseCase;

use DSI\Entity\Organisation;
use DSI\Entity\OrganisationProject;
use DSI\Repository\OrganisationProjectRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Service\ErrorHandler;

class CalculateOrganisationPartnersCount
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationRepository */
    private $organisationRepo;

    /** @var OrganisationProjectRepository */
    private $organisationProjectRepo;

    /** @var CalculateOrganisationPartnersCount_Data */
    private $data;

    public function __construct()
    {
        $this->data = new CalculateOrganisationPartnersCount_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->organisationRepo = new OrganisationRepository();
        $this->organisationProjectRepo = new OrganisationProjectRepository();

        if ($this->data()->organisationID <= 0) {
            $this->errorHandler->addTaggedError('organisation', 'Invalid organisation ID');
            throw $this->errorHandler;
        }

        $organisation = $this->organisationRepo->getById($this->data()->organisationID);
        $projectsInvolved = $this->getInvolvedProjects($organisation);
        $organisationsInvolved = $this->getInvolvedOrganisations($projectsInvolved);
        $organisationsInvolved = array_unique($organisationsInvolved);
        $organisationsInvolved = array_filter($organisationsInvolved);
        $organisationsInvolved = $this->excludeCurrentOrganisation($organisationsInvolved, $organisation);
        
        $organisation->setPartnersCount(
            count($organisationsInvolved)
        );
        $this->organisationRepo->save($organisation);
    }

    /**
     * @return CalculateOrganisationPartnersCount_Data
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @param $organisation
     * @return mixed
     */
    private function getInvolvedProjects(Organisation $organisation)
    {
        $projectsInvolved = array_map(
            function (OrganisationProject $organisationProject) {
                return $organisationProject->getProjectID();
            },
            $this->organisationProjectRepo->getByOrganisationID($organisation->getId())
        );
        return $projectsInvolved;
    }

    /**
     * @param $projectsInvolved
     * @return mixed
     */
    private function getInvolvedOrganisations($projectsInvolved)
    {
        $organisationsInvolved = array_map(
            function (OrganisationProject $organisationProject) {
                return $organisationProject->getOrganisationID();
            },
            $this->organisationProjectRepo->getByProjectIDs($projectsInvolved)
        );
        return $organisationsInvolved;
    }

    /**
     * @param $organisationsInvolved
     * @param $organisation
     * @return mixed
     */
    private function excludeCurrentOrganisation($organisationsInvolved, Organisation $organisation)
    {
        $organisationsInvolved = array_diff($organisationsInvolved, [$organisation->getId()]);
        return $organisationsInvolved;
    }
}

class CalculateOrganisationPartnersCount_Data
{
    /** @var int */
    public $organisationID;
}