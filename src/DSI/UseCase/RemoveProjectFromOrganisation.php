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

class RemoveProjectFromOrganisation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationProjectRepo */
    private $organisationProjectRepo;

    /** @var OrganisationRepo */
    private $organisationRepository;

    /** @var ProjectRepo */
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
        $this->organisationProjectRepo = new OrganisationProjectRepo();
        $this->organisationRepository = new OrganisationRepoInAPC();
        $this->projectRepository = new ProjectRepoInAPC();

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