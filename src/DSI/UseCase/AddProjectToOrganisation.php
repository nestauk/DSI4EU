<?php

namespace DSI\UseCase;

use DSI\Entity\OrganisationProject;
use DSI\Entity\Project;
use DSI\Repository\OrganisationProjectRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\ProjectRepositoryInAPC;
use DSI\Service\ErrorHandler;

class AddProjectToOrganisation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationProjectRepository */
    private $organisationProjectRepo;

    /** @var OrganisationRepository */
    private $organisationRepository;

    /** @var ProjectRepository */
    private $projectRepository;

    /** @var AddProjectToOrganisation_Data */
    private $data;

    public function __construct()
    {
        $this->data = new AddProjectToOrganisation_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->organisationProjectRepo = new OrganisationProjectRepository();
        $this->organisationRepository = new OrganisationRepository();
        $this->projectRepository = new ProjectRepositoryInAPC();

        $this->checkIfTheOrganisationAlreadyHasTheProject();

        $project = $this->projectRepository->getById($this->data()->projectID);

        $this->addProjectToOrganisation($project);
        $this->setProjectOrganisationsCount($project);
        $this->setOrganisationPartnersCount();
        $this->setOrganisationProjectsCount();
    }

    /**
     * @return AddProjectToOrganisation_Data
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
    private function addProjectToOrganisation($project)
    {
        $organisationProject = new OrganisationProject();
        $organisationProject->setProject($project);
        $organisationProject->setOrganisation($this->organisationRepository->getById($this->data()->organisationID));
        $this->organisationProjectRepo->add($organisationProject);
    }

    private function checkIfTheOrganisationAlreadyHasTheProject()
    {
        if ($this->organisationProjectRepo->organisationHasProject($this->data()->organisationID, $this->data()->projectID)) {
            $this->errorHandler->addTaggedError('project', __('The organisation already has this project'));
            $this->errorHandler->throwIfNotEmpty();
        }
    }
}

class AddProjectToOrganisation_Data
{
    /** @var int */
    public $projectID;

    /** @var int */
    public $organisationID;
}