<?php

namespace DSI\Controller;

use DSI\Repository\CountryRepo;
use DSI\Service\Auth;
use Services\URL;

class ListCountriesController
{
    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();

        $countryRepo = new CountryRepo();
        $countries = [];
        foreach ($countryRepo->getAll() AS $country) {
            $countries[] = [
                'id' => $country->getId(),
                'text' => $country->getName(),
            ];
        }

        echo json_encode($countries);
        die();
    }
}