<?php

namespace DSI\Controller;

use DSI\Entity\Organisation;
use DSI\Repository\OrganisationNetworkTagRepository;
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

        if (isset($_GET['download'])) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="dsi-organisations.' . $this->format . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
        }

        if ($this->format == 'json') {
            $this->exportJson($organisations);
        } elseif ($this->format == 'csv') {
            $this->exportCsv($organisations);
        } elseif ($this->format == 'xml') {
            $this->exportXml($organisations);
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
                'networkTags' => $this->getNetworkTags($organisation),
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
            'Network Tags',
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
                'networkTags' => implode(', ', $this->getNetworkTags($organisation)),
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

            $xmlTags = $xmlOrganisation->addChild('networkTags');
            foreach ($this->getNetworkTags($organisation) AS $tagID)
                $xmlTags->addChild('tag', htmlspecialchars($tagID));
        }

        header('Content-type: text/xml');
        print($xml->asXML());
    }

    private function getOrganisationProjectIDs(Organisation $organisation)
    {
        return (new OrganisationProjectRepository())->getProjectIDsForOrganisation($organisation);
    }

    private function getNetworkTags(Organisation $organisation)
    {
        return (new OrganisationNetworkTagRepository())->getTagNamesByOrganisation($organisation);
    }
}