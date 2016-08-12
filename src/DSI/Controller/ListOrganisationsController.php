<?php

namespace DSI\Controller;

use DSI\Repository\OrganisationRepository;
use DSI\Repository\OrganisationRepositoryInAPC;
use DSI\Service\Auth;
use DSI\Service\URL;

class ListOrganisationsController
{
    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());

        $organisationRepositoryInAPC = new OrganisationRepositoryInAPC();
        $organisations = [];
        foreach ($organisationRepositoryInAPC->getAll() AS $organisation) {
            $organisations[] = [
                'id' => $organisation->getId(),
                'text' => $organisation->getName(),
                'url' => URL::organisation($organisation),
            ];
        }

        usort($organisations, function ($a, $b) {
            return ($a['text'] <= $b['text']) ? -1 : 1;
        });

        echo json_encode($organisations);
        die();
    }
}