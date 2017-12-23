<?php

namespace DSI\UseCase;

use DSI\Repository\OrganisationProjectRepo;
use DSI\Repository\OrganisationRepo;
use DSI\Service\ErrorHandler;

class CalculateOrganisationProjectsCount
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationRepo */
    private $organisationRepo;

    /** @var OrganisationProjectRepo */
    private $organisationProjectRepo;

    /** @var CalculateOrganisationProjectsCount_Data */
    private $data;

    public function __construct()
    {
        $this->data = new CalculateOrganisationProjectsCount_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->organisationRepo = new OrganisationRepo();
        $this->organisationProjectRepo = new OrganisationProjectRepo();

        if ($this->data()->organisationID <= 0) {
            $this->errorHandler->addTaggedError('organisation', 'Invalid organisation ID');
            throw $this->errorHandler;
        }

        $organisation = $this->organisationRepo->getById($this->data()->organisationID);
        $projectsCnt = count($this->organisationProjectRepo->getByOrganisationID($organisation->getId()));

        $organisation->setProjectsCount(
            $projectsCnt
        );
        $this->organisationRepo->save($organisation);
    }

    /**
     * @return CalculateOrganisationProjectsCount_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class CalculateOrganisationProjectsCount_Data
{
    /** @var int */
    public $organisationID;
}