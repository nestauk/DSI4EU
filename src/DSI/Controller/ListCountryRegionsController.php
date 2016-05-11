<?php

namespace DSI\Controller;

use DSI\Repository\CountryRegionRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class ListCountryRegionsController
{
    /** @var ListCountryRegionsController_Data */
    private $data;

    public function __construct()
    {
        $this->data = new ListCountryRegionsController_Data();
    }

    public function exec()
    {
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo(URL::login());

        $countryRegionsRepo = new CountryRegionRepository();
        $countryRegions = [];
        foreach ($countryRegionsRepo->getAllByCountry($this->data()->countryID) AS $countryRegion) {
            $countryRegions[] = [
                'id' => $countryRegion->getName(),
                'text' => $countryRegion->getName(),
            ];
        }

        echo json_encode($countryRegions);
        die();
    }

    /**
     * @return ListCountryRegionsController_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class ListCountryRegionsController_Data
{
    /** @var int */
    public $countryID;
}