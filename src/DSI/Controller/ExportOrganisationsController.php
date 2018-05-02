<?php

namespace DSI\Controller;

use DSI\Entity\Organisation;
use DSI\Entity\OrganisationNetworkTag;
use DSI\Repository\OrganisationNetworkTagRepo;
use DSI\Repository\OrganisationProjectRepo;
use DSI\Repository\OrganisationRepo;
use DSI\Repository\OrganisationRepoInAPC;
use DSI\Repository\OrganisationTagRepo;
use DSI\Service\Auth;
use Services\URL;

class ExportOrganisationsController
{
    public $format = 'json';

    /** @var URL */
    private $urlHandler;

    public function exec()
    {
        set_time_limit(0);
        $this->urlHandler = $urlHandler = new URL();
        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();

        $organisations = (new OrganisationRepoInAPC())->getAll();

        if (isset($_GET['download'])) {
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename="dsi-organisations.' . $this->format . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
        }

        if ($this->format == 'json') {
            header('Content-Type: application/json');
            $this->exportJson($organisations);
        } elseif ($this->format == 'csv') {
            header('Content-Type: text/csv');
            $this->exportCsv($organisations);
        } elseif ($this->format == 'xml') {
            header('Content-Type: text/xml');
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
                'organisation_id' => $organisation->getId(),
                'organisation_name' => $organisation->getName(),
                'website' => $organisation->getExternalUrl(),
                'short_description' => $organisation->getShortDescription(),
                'long_description' => $organisation->getDescription(),
                'country' => $organisation->getCountryName(),
                'region' => $organisation->getRegionName(),
                'latitude' => $organisation->getRegionLatitude(),
                'longitude' => $organisation->getRegionLongitude(),
                'address' => $organisation->getAddress(),
                'organisation_type' => $this->getType($organisation),
                'organisation_size' => $organisation->getSizeName(),
                'startDate' => $organisation->getStartDate(),
                'linked_project_ids' => $this->getOrganisationProjectIDs($organisation),
                'tags' => $this->getTags($organisation),
                'networkTags' => $this->getNetworkTags($organisation),
                'created' => $organisation->getCreationTime('Y-m-d'),
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
            'Organisation ID',
            'Organisation name',
            'Website',
            'Short description',
            'Long description',
            'Country',
            'Region',
            'Latitude',
            'Longitude',
            'Address',
            'Organisation type',
            'Organisation size',
            'Start date',
            'Linked project IDs',
            'Tags',
            'Network tags',
            'Created',
        ]);

        foreach ($organisations as $organisation) {
            fputcsv($fp, [
                'organisation_id' => $organisation->getId(),
                'organisation_name' => $organisation->getName(),
                'website' => $organisation->getExternalUrl(),
                'short_description' => $organisation->getShortDescription(),
                'long_description' => $organisation->getDescription(),
                'country' => $organisation->getCountryName(),
                'region' => $organisation->getRegionName(),
                'latitude' => $organisation->getRegionLatitude(),
                'longitude' => $organisation->getRegionLongitude(),
                'address' => $organisation->getAddress(),
                'organisation_type' => $organisation->getTypeName(),
                'organisation_size' => $organisation->getSizeName(),
                'start_date' => $organisation->getStartDate(),
                'linked_project_ids' => implode(', ', $this->getOrganisationProjectIDs($organisation)),
                'tags' => implode(', ', $this->getTags($organisation)),
                'networkTags' => implode(', ', $this->getNetworkTagNames($organisation)),
                'created' => $organisation->getCreationTime('Y-m-d'),
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
            $xmlOrganisation->addChild('organisation_id', $organisation->getId());
            $xmlOrganisation->addChild('organisation_name', htmlspecialchars($organisation->getName()));
            $xmlOrganisation->addChild('website', htmlspecialchars($organisation->getExternalUrl()));
            $xmlOrganisation->addChild('short_description', htmlspecialchars($organisation->getShortDescription()));
            $xmlOrganisation->addChild('long_description', htmlspecialchars($organisation->getDescription()));
            $xmlOrganisation->addChild('country', htmlspecialchars($organisation->getCountryName()));
            $xmlOrganisation->addChild('region', htmlspecialchars($organisation->getRegionName()));
            $xmlOrganisation->addChild('latitude', htmlspecialchars($organisation->getRegionLatitude()));
            $xmlOrganisation->addChild('longitude', htmlspecialchars($organisation->getRegionLongitude()));
            $xmlOrganisation->addChild('address', htmlspecialchars($organisation->getAddress()));
            $xmlOrganisation->addChild('organisation_size', htmlspecialchars($organisation->getSizeName()));
            $xmlOrganisation->addChild('start_date', htmlspecialchars($organisation->getStartDate()));

            $xmlType = $xmlOrganisation->addChild('organisation_type');
            $xmlType->addChild('id', $organisation->getTypeId());
            $xmlType->addChild('name', htmlspecialchars($organisation->getTypeName()));

            $xmlProjects = $xmlOrganisation->addChild('linked_project_ids');
            foreach ($this->getOrganisationProjectIDs($organisation) AS $projectID)
                $xmlProjects->addChild('project', htmlspecialchars($projectID));

            $xmlTags = $xmlOrganisation->addChild('tags');
            foreach ($this->getTags($organisation) AS $tag)
                $xmlTags->addChild('tag', htmlspecialchars($tag));

            $xmlTags = $xmlOrganisation->addChild('networkTags');
            foreach ($this->getNetworkTags($organisation) AS $tag) {
                $xmlTag = $xmlTags->addChild('tag');
                $xmlTag->addChild('id', $tag['id']);
                $xmlTag->addChild('name', $tag['name']);
            }

            $xmlOrganisation->addChild('created', htmlspecialchars($organisation->getCreationTime('Y-m-d')));
        }

        header('Content-type: text/xml');
        print($xml->asXML());
    }

    private function getOrganisationProjectIDs(Organisation $organisation)
    {
        return (new OrganisationProjectRepo())->getProjectIDsForOrganisation($organisation);
    }

    private function getNetworkTags(Organisation $organisation)
    {
        return (new OrganisationNetworkTagRepo())->getTagDataByOrganisation($organisation);
    }

    private function getNetworkTagNames(Organisation $organisation)
    {
        return (new OrganisationNetworkTagRepo())->getTagNamesByOrganisation($organisation);
    }

    private function getTags(Organisation $organisation)
    {
        return (new OrganisationTagRepo())->getTagNamesByOrganisation($organisation);
    }

    private function getType(Organisation $organisation)
    {
        return [
            'id' => $organisation->getTypeId(),
            'name' => $organisation->getTypeName(),
        ];
    }
}