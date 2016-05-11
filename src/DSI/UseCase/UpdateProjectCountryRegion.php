<?php

namespace DSI\UseCase;

use DSI\Repository\CountryRegionRepository;
use DSI\Repository\ProjectRepository;
use DSI\Service\ErrorHandler;

class UpdateProjectCountryRegion
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UpdateProjectCountryRegion_Data */
    private $data;

    /** @var ProjectRepository */
    private $projectRepo;

    /** @var CountryRegionRepository */
    private $countryRegionRepo;

    public function __construct()
    {
        $this->data = new UpdateProjectCountryRegion_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectRepo = new ProjectRepository();
        $this->countryRegionRepo = new CountryRegionRepository();

        if ($this->data()->countryID == 0)
            $this->errorHandler->addTaggedError('country', 'Please select a country');

        if ($this->data()->projectID == 0)
            $this->errorHandler->addTaggedError('project', 'Please select a project');

        if ($this->data()->region == '')
            $this->errorHandler->addTaggedError('region', 'Please specify a region');

        $this->errorHandler->throwIfNotEmpty();

        if ($this->countryRegionRepo->nameExists($this->data()->countryID, $this->data()->region)) {
            $countryRegion = $this->countryRegionRepo->getByName($this->data()->countryID, $this->data()->region);
        } else {
            $createCountryRegionCmd = new CreateCountryRegion();
            $createCountryRegionCmd->data()->countryID = $this->data()->countryID;
            $createCountryRegionCmd->data()->name = $this->data()->region;
            $createCountryRegionCmd->exec();
            $countryRegion = $createCountryRegionCmd->getCountryRegion();
        }

        $project = $this->projectRepo->getById($this->data()->projectID);
        $project->setCountryRegion($countryRegion);
        $this->projectRepo->save($project);
    }

    /**
     * @return UpdateProjectCountryRegion_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class UpdateProjectCountryRegion_Data
{
    /** @var string */
    public $region;

    /** @var int */
    public $projectID,
        $countryID;
}