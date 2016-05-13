<?php

namespace DSI\UseCase;

use DSI\Entity\OrganisationProject;
use DSI\Repository\OrganisationProjectRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\ProjectRepository;
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
        $this->projectRepository = new ProjectRepository();

        if ($this->organisationProjectRepo->organisationHasProject($this->data()->organisationID, $this->data()->projectID)) {
            $this->errorHandler->addTaggedError('project', 'The organisation already has this project');
            $this->errorHandler->throwIfNotEmpty();
        }

        $project = $this->projectRepository->getById($this->data()->projectID);

        $organisationProject = new OrganisationProject();
        $organisationProject->setProject($project);
        $organisationProject->setOrganisation($this->organisationRepository->getById($this->data()->organisationID));
        $this->organisationProjectRepo->add($organisationProject);

        $organisationsCount = count($this->organisationProjectRepo->getByProjectID($project->getId()));
        $project->setOrganisationsCount($organisationsCount);
        $this->projectRepository->save($project);
    }

    /**
     * @return AddProjectToOrganisation_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class AddProjectToOrganisation_Data
{
    /** @var int */
    public $projectID;

    /** @var int */
    public $organisationID;
}