<?php

namespace DSI\Controller;

use DSI\Repository\CountryRegionRepo;
use DSI\Service\Auth;
use Services\URL;

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
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());

        $countryRegionsRepo = new CountryRegionRepo();
        $countryRegions = [];
        foreach ($countryRegionsRepo->getAllByCountryId($this->data()->countryID) AS $countryRegion) {
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