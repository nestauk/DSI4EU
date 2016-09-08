<?php

namespace DSI\Controller;

use DSI\Repository\CountryRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class ListCountriesController
{
    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());
        $loggedInUser = $authUser->getUser();

        $countryRepo = new CountryRepository();
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