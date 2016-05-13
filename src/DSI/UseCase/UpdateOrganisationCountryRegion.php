<?php

namespace DSI\UseCase;

use DSI\Repository\CountryRegionRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Service\ErrorHandler;

class UpdateOrganisationCountryRegion
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UpdateOrganisationCountryRegion_Data */
    private $data;

    /** @var OrganisationRepository */
    private $organisationRepo;

    /** @var CountryRegionRepository */
    private $countryRegionRepo;

    public function __construct()
    {
        $this->data = new UpdateOrganisationCountryRegion_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->organisationRepo = new OrganisationRepository();
        $this->countryRegionRepo = new CountryRegionRepository();

        if ($this->data()->countryID == 0)
            $this->errorHandler->addTaggedError('country', 'Please select a country');

        if ($this->data()->organisationID == 0)
            $this->errorHandler->addTaggedError('organisation', 'Please select a organisation');

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

        $organisation = $this->organisationRepo->getById($this->data()->organisationID);
        $organisation->setCountryRegion($countryRegion);
        $this->organisationRepo->save($organisation);
    }

    /**
     * @return UpdateOrganisationCountryRegion_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class UpdateOrganisationCountryRegion_Data
{
    /** @var string */
    public $region;

    /** @var int */
    public $organisationID,
        $countryID;
}