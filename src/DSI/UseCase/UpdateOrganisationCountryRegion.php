<?php

namespace DSI\UseCase;

use DSI\Repository\CountryRegionRepo;
use DSI\Repository\OrganisationRepo;
use DSI\Service\ErrorHandler;

class UpdateOrganisationCountryRegion
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UpdateOrganisationCountryRegion_Data */
    private $data;

    /** @var OrganisationRepo */
    private $organisationRepo;

    /** @var CountryRegionRepo */
    private $countryRegionRepo;

    public function __construct()
    {
        $this->data = new UpdateOrganisationCountryRegion_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->organisationRepo = new OrganisationRepo();
        $this->countryRegionRepo = new CountryRegionRepo();

        if ($this->data()->countryID == 0)
            $this->errorHandler->addTaggedError('country', __('Please select a country'));

        if ($this->data()->organisationID == 0)
            $this->errorHandler->addTaggedError('organisation', 'Please select an organisation');

        if ($this->data()->region == '')
            $this->errorHandler->addTaggedError('region', __('Please specify a region'));

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