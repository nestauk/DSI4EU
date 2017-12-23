<?php

namespace DSI\Controller\CLI;

use DSI\Repository\OrganisationRepo;
use DSI\UseCase\CalculateOrganisationPartnersCount;

class UpdateOrganisationsPartnersCountController
{
    public function exec()
    {
        $organisationRepo = new OrganisationRepo();
        foreach($organisationRepo->getAll() AS $organisation){
            $exec = new CalculateOrganisationPartnersCount();
            $exec->setOrganisation($organisation);
            $exec->exec();
        }
    }
}