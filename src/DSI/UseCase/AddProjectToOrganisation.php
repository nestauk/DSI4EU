<?php

namespace DSI\UseCase;

use DSI\Entity\OrganisationProject;
use DSI\Entity\Project;
use DSI\Repository\OrganisationProjectRepo;
use DSI\Repository\OrganisationRepo;
use DSI\Repository\OrganisationRepoInAPC;
use DSI\Repository\ProjectRepo;
use DSI\Repository\ProjectRepoInAPC;
use DSI\Service\ErrorHandler;

class AddProjectToOrganisation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationProjectRepo */
    private $organisationProjectRepo;

    /** @var OrganisationRepo */
    private $organisationRepository;

    /** @var ProjectRepo */
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
        $this->organisationProjectRepo = new OrganisationProjectRepo();
        $this->organisationRepository = new OrganisationRepoInAPC();
        $this->projectRepository = new ProjectRepoInAPC();

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