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

    /** @var Organisation */
    private $organisation;

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->organisationRepo = new OrganisationRepository();
        $this->organisationProjectRepo = new OrganisationProjectRepository();

        if ($this->organisation->getId() <= 0) {
            $this->errorHandler->addTaggedError('organisation', 'Invalid organisation ID');
            throw $this->errorHandler;
        }

        $projectsInvolved = $this->getInvolvedProjects($this->organisation);
        $organisationsInvolved = $this->getInvolvedOrganisations($projectsInvolved);
        $organisationsInvolved = array_unique($organisationsInvolved);
        $organisationsInvolved = array_filter($organisationsInvolved);
        $organisationsInvolved = $this->excludeCurrentOrganisation($organisationsInvolved, $this->organisation);

        $this->organisation->setPartnersCount(
            count($organisationsInvolved)
        );
        $this->organisationRepo->save($this->organisation);
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

    /**
     * @param Organisation $organisation
     */
    public function setOrganisation(Organisation $organisation)
    {
        $this->organisation = $organisation;
    }
}