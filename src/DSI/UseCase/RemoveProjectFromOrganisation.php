<?php

namespace DSI\UseCase;

use DSI\Entity\OrganisationProject;
use DSI\Entity\Project;
use DSI\Repository\OrganisationProjectRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\ProjectRepository;
use DSI\Service\ErrorHandler;

class RemoveProjectFromOrganisation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationProjectRepository */
    private $organisationProjectRepo;

    /** @var OrganisationRepository */
    private $organisationRepository;

    /** @var ProjectRepository */
    private $projectRepository;

    /** @var RemoveProjectFromOrganisation_Data */
    private $data;

    public function __construct()
    {
        $this->data = new RemoveProjectFromOrganisation_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->organisationProjectRepo = new OrganisationProjectRepository();
        $this->organisationRepository = new OrganisationRepository();
        $this->projectRepository = new ProjectRepository();

        $this->checkIfTheOrganisationHasTheProject();

        $project = $this->projectRepository->getById($this->data()->projectID);

        $this->RemoveProjectFromOrganisation($project);
        $this->setProjectOrganisationsCount($project);
        $this->setOrganisationPartnersCount();
        $this->setOrganisationProjectsCount();
    }

    /**
     * @return RemoveProjectFromOrganisation_Data
     */
    public function data()
    {
        return $this->data;
    }

    private function setOrganisationPartnersCount()
    {
        $calculateOrganisationPartnersCountCmd = new CalculateOrganisationPartnersCount();
        $calculateOrganisationPartnersCountCmd->setOrganisation(
            $this->organisationRepository->getById($this->data()->organisationID)
        );
        $calculateOrganisationPartnersCountCmd->exec();
    }

    private function setOrganisationProjectsCount()
    {
        $calculateOrganisationProjectsCountCmd = new CalculateOrganisationProjectsCount();
        $calculateOrganisationProjectsCountCmd->data()->organisationID = $this->data()->organisationID;
        $calculateOrganisationProjectsCountCmd->exec();
    }

    /**
     * @param $project
     * @throws \DSI\NotFound
     */
    private function setProjectOrganisationsCount(Project $project)
    {
        $organisationsCount = count($this->organisationProjectRepo->getByProjectID($project->getId()));
        $project->setOrganisationsCount($organisationsCount);
        $this->projectRepository->save($project);
    }

    /**
     * @param $project
     * @throws \DSI\DuplicateEntry
     */
    private function RemoveProjectFromOrganisation($project)
    {
        $organisationProject = new OrganisationProject();
        $organisationProject->setProject($project);
        $organisationProject->setOrganisation($this->organisationRepository->getById($this->data()->organisationID));
        $this->organisationProjectRepo->remove($organisationProject);
    }

    private function checkIfTheOrganisationHasTheProject()
    {
        if (!$this->organisationProjectRepo->organisationHasProject($this->data()->organisationID, $this->data()->projectID)) {
            $this->errorHandler->addTaggedError('project', 'The organisation does not have this project');
            $this->errorHandler->throwIfNotEmpty();
        }
    }
}

class RemoveProjectFromOrganisation_Data
{
    /** @var int */
    public $projectID;

    /** @var int */
    public $organisationID;
}