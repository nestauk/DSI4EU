<?php

namespace DSI\Controller;

use DSI\Repository\OrganisationRepo;
use DSI\Repository\OrganisationRepoInAPC;
use DSI\Service\Auth;
use Services\URL;

class ListOrganisationsController
{
    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());

        $organisationRepositoryInAPC = new OrganisationRepoInAPC();
        $organisations = [];
        foreach ($organisationRepositoryInAPC->getAll() AS $organisation) {
            $organisations[] = [
                'id' => $organisation->getId(),
                'text' => $organisation->getName(),
                'url' => $urlHandler->organisation($organisation),
            ];
        }

        usort($organisations, function ($a, $b) {
            return ($a['text'] <= $b['text']) ? -1 : 1;
        });

        echo json_encode($organisations);
        die();
    }
}