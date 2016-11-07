<?php

namespace DSI\Controller;

use DSI\Entity\Organisation;
use DSI\Repository\OrganisationProjectRepository;
use DSI\Repository\OrganisationRepositoryInAPC;
use DSI\Service\Auth;
use DSI\Service\URL;

class ExportOrganisationsController
{
    public $format = 'json';

    /** @var URL */
    private $urlHandler;

    public function exec()
    {
        $this->urlHandler = $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());

        $organisations = (new OrganisationRepositoryInAPC())->getAll();
        if ($this->format == 'json') {
            return $this->exportJson($organisations);
        } elseif ($this->format == 'csv') {
            return $this->exportCsv($organisations);
        } elseif ($this->format == 'xml') {
            return $this->exportXml($organisations);
        } else {
            return false;
        }
    }

    /**
     * @param Organisation[] $organisations
     */
    private function exportJson($organisations)
    {
        echo json_encode(array_map(function (Organisation $organisation) {
            return [
                'id' => $organisation->getId(),
                'name' => $organisation->getName(),
                'website' => $organisation->getExternalUrl(),
                'description' => $organisation->getShortDescription(),
                'country' => $organisation->getCountryName(),
                'region' => $organisation->getRegionName(),
                'address' => $organisation->getAddress(),
                'type' => $organisation->getTypeName(),
                'size' => $organisation->getSizeName(),
                'startDate' => $organisation->getStartDate(),
                'projects' => $this->getOrganisationProjectIDs($organisation),
            ];
        }, $organisations));
    }

    /**
     * @param Organisation[] $organisations
     */
    private function exportCsv($organisations)
    {
        $fp = fopen("php://output", 'w');

        fputcsv($fp, [
            'Id',
            'Name',
            'Website',
            'Description',
            'Country',
            'Region',
            'Address',
            'Type',
            'Size',
            'Start Date',
            'Projects',
        ]);

        foreach ($organisations as $organisation) {
            fputcsv($fp, [
                'id' => $organisation->getId(),
                'name' => $organisation->getName(),
                'website' => $organisation->getExternalUrl(),
                'description' => $organisation->getShortDescription(),
                'country' => $organisation->getCountryName(),
                'region' => $organisation->getRegionName(),
                'address' => $organisation->getAddress(),
                'type' => $organisation->getTypeName(),
                'size' => $organisation->getSizeName(),
                'startDate' => $organisation->getStartDate(),
                'projects' => implode(', ', $this->getOrganisationProjectIDs($organisation)),
            ]);
        }

        fclose($fp);
    }

    /**
     * @param Organisation[] $organisations
     */
    private function exportXml($organisations)
    {
        $xml = new \SimpleXMLElement('<xml/>');

        foreach ($organisations AS $organisation) {
            $xmlOrganisation = $xml->addChild('organisation');
            $xmlOrganisation->addChild('id', $organisation->getId());
            $xmlOrganisation->addChild('name', htmlspecialchars($organisation->getName()));
            $xmlOrganisation->addChild('website', htmlspecialchars($organisation->getExternalUrl()));
            $xmlOrganisation->addChild('description', htmlspecialchars($organisation->getShortDescription()));
            $xmlOrganisation->addChild('country', htmlspecialchars($organisation->getCountryName()));
            $xmlOrganisation->addChild('region', htmlspecialchars($organisation->getRegionName()));
            $xmlOrganisation->addChild('address', htmlspecialchars($organisation->getAddress()));
            $xmlOrganisation->addChild('type', htmlspecialchars($organisation->getTypeName()));
            $xmlOrganisation->addChild('size', htmlspecialchars($organisation->getSizeName()));
            $xmlOrganisation->addChild('startDate', htmlspecialchars($organisation->getStartDate()));

            $xmlProjects = $xmlOrganisation->addChild('projects');
            foreach ($this->getOrganisationProjectIDs($organisation) AS $projectID)
                $xmlProjects->addChild('project', htmlspecialchars($projectID));
        }

        header('Content-type: text/xml');
        print($xml->asXML());
    }

    private function getOrganisationProjectIDs(Organisation $organisation)
    {
        return (new OrganisationProjectRepository())->getProjectIDsForOrganisation($organisation);
    }
}