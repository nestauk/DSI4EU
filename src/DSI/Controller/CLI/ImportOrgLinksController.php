<?php

namespace DSI\Controller\CLI;

set_time_limit(0);

use DSI\Entity\Organisation;
use DSI\Entity\User;
use DSI\NotFound;
use DSI\Repository\OrganisationLinkRepository;
use DSI\Repository\OrganisationRepository;
use DSI\UseCase\UpdateOrganisation;

class ImportOrgLinksController
{
    /** @var  OrganisationRepository */
    private $orgRepo;

    public function exec()
    {
        $this->orgRepo = new OrganisationRepository();
        $this->importOrganisationsFrom(__DIR__ . '/../../../import/orgs.csv');
    }

    /**
     * @param $file
     */
    private function importOrganisationsFrom($file)
    {
        $row = 0;
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle)) !== FALSE) {
                $row++;

                if ($row == 1) {
                    // var_dump(['skip', $data]);
                    continue;
                }

                $this->importOrganisationLinksRow($data);
            }
            fclose($handle);
        }
    }

    /**
     * @param $data
     */
    private function importOrganisationLinksRow($data)
    {
        try{
            $organisation = $this->orgRepo->getByImportID($data[0]);
            $organisationLinks = (new OrganisationLinkRepository())->getLinksByOrganisationID(
                $organisation->getId()
            );

            if ($data[4]) {
                $url = 'https://twitter.com/' . $data[4];
                if (!in_array($url, $organisationLinks)) {
                    $organisationLinks[] = $url;
                    echo "  add {$url}" . PHP_EOL;
                } else {
                    echo "exists {$url}" . PHP_EOL;
                }
            };

            if ($data[5]) {
                $url = $data[5];
                if(substr($url, 0, 4) != 'http')
                    $url = 'http://' . $url;

                if (!in_array($url, $organisationLinks)) {
                    $organisationLinks[] = $url;
                    echo "  add {$url}" . PHP_EOL;
                } else {
                    echo "exists {$url}" . PHP_EOL;
                }
            };

            $organisationLinks = array_filter($organisationLinks);

            $updateOrganisation = new UpdateOrganisation();
            $updateOrganisation->data()->organisation = $organisation;
            $updateOrganisation->data()->executor = $this->getRootUser();
            $updateOrganisation->data()->links = $organisationLinks;
            $updateOrganisation->exec();
        } catch (NotFound $e){
            echo 'Non existent organisation: ' . $data[1] . PHP_EOL;
        }

        /*
        var_dump($data);
        $organisation = new Organisation();
        $organisation->setOwner($this->getRootUser());

        $organisation->setImportID($data[0]);
        $organisation->setName($data[1]);
        $this->setOrganisationType($data[2], $organisation);
        $this->setOrganisationSize($data[3], $organisation);
        $organisation->setAddress("{$data[11]},\n{$data[12]}");
        // ignore region $data[13]
        $this->setCountryRegion($organisation, $data[14], $data[15]);

        (new OrganisationRepository())->insert($organisation);


        // TODO create organisation URLs
        // TODO add twitter $data[4]
        // TODO add website $data[5]
        // TODO add lat + long $data[8,9]
        $organisationLinks = (new OrganisationLinkRepository())->getLinksByOrganisationID(
            $organisation->getId()
        );
        $updateOrganisation = new UpdateOrganisation();
        $updateOrganisation->data()->organisation = $organisation;
        $updateOrganisation->data()->executor = $this->getRootUser();
        if ($data[4]) {
            $url = 'https://twitter.com/' . $data[4];
            if (!in_array($url, $organisationLinks))
                $organisationLinks[] = 'https://twitter.com/' . $data[4];
        };
        if ($data[5]) {
            $url = 'https://twitter.com/' . $data[4];
            if (!in_array($url, $organisationLinks))
                $organisationLinks[] = 'https://twitter.com/' . $data[4];
        };
        */
    }

    /**
     * @return User
     */
    private function getRootUser()
    {
        $owner = new User();
        $owner->setId(1);
        return $owner;
    }
}