<?php

namespace DSI\Controller;

use DSI\Entity\Project;
use DSI\Repository\OrganisationProjectRepository;
use DSI\Repository\ProjectDsiFocusTagRepository;
use DSI\Repository\ProjectImpactTagARepository;
use DSI\Repository\ProjectImpactTagCRepository;
use DSI\Repository\ProjectNetworkTagRepository;
use DSI\Repository\ProjectRepositoryInAPC;
use DSI\Repository\ProjectTagRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class ExportProjectsController
{
    public $format = 'json';

    /** @var URL */
    private $urlHandler;

    public function exec()
    {
        $this->urlHandler = $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());

        $projects = (new ProjectRepositoryInAPC())->getAll();

        if (isset($_GET['download'])) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="dsi-projects.' . $this->format . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
        }

        if ($this->format == 'json') {
            $this->exportJson($projects);
        } elseif ($this->format == 'csv') {
            $this->exportCsv($projects);
        } elseif ($this->format == 'xml') {
            $this->exportXml($projects);
        }
    }

    /**
     * @param Project[] $projects
     */
    private function exportJson($projects)
    {
        echo json_encode(array_map(function (Project $project) {
            return [
                'id' => $project->getId(),
                'name' => $project->getName(),
                'website' => $project->getExternalUrl(),
                'description' => $project->getShortDescription(),
                'startDate' => $project->getStartDate(),
                'endDate' => $project->getEndDate(),
                'country' => $project->getCountryName(),
                'region' => $project->getRegionName(),
                'organisations' => $this->getProjectOrganisationIDs($project),
                'tags' => $this->getTags($project),
                'networkTags' => $this->getNetworkTags($project),
                'supports' => $this->getSupportsTags($project),
                'focus' => $this->getFocusTags($project),
                'technology' => $this->getTechnologyTags($project),
            ];
        }, $projects));
    }

    /**
     * @param Project[] $projects
     */
    private function exportCsv($projects)
    {
        $fp = fopen("php://output", 'w');

        fputcsv($fp, [
            'Id',
            'Name',
            'Website',
            'Description',
            'Start Date',
            'End Date',
            'Country',
            'Region',
            'Organisations',
            'Tags',
            'Network Tags',
            'Supports',
            'Focus',
            'Technology',
        ]);

        foreach ($projects as $project) {
            fputcsv($fp, [
                'id' => $project->getId(),
                'name' => $project->getName(),
                'website' => $project->getExternalUrl(),
                'description' => $project->getShortDescription(),
                'startDate' => $project->getStartDate(),
                'endDate' => $project->getEndDate(),
                'country' => $project->getCountryName(),
                'region' => $project->getRegionName(),
                'organisations' => implode(', ', $this->getProjectOrganisationIDs($project)),
                'tags' => implode(', ', $this->getTags($project)),
                'networkTags' => implode(', ', $this->getNetworkTags($project)),
                'supports' => implode(', ', $this->getSupportsTags($project)),
                'focus' => implode(', ', $this->getFocusTags($project)),
                'technology' => implode(', ', $this->getTechnologyTags($project)),
            ]);
        }

        fclose($fp);
    }

    /**
     * @param Project[] $projects
     */
    private function exportXml($projects)
    {
        $xml = new \SimpleXMLElement('<xml/>');

        foreach ($projects AS $project) {
            $xmlProject = $xml->addChild('project');
            $xmlProject->addChild('id', $project->getId());
            $xmlProject->addChild('name', htmlspecialchars($project->getName()));
            $xmlProject->addChild('website', htmlspecialchars($project->getExternalUrl()));
            $xmlProject->addChild('description', htmlspecialchars($project->getShortDescription()));
            $xmlProject->addChild('startDate', htmlspecialchars($project->getStartDate()));
            $xmlProject->addChild('endDate', htmlspecialchars($project->getEndDate()));
            $xmlProject->addChild('country', htmlspecialchars($project->getCountryName()));
            $xmlProject->addChild('region', htmlspecialchars($project->getRegionName()));

            $xmlOrganisations = $xmlProject->addChild('organisations');
            foreach ($this->getProjectOrganisationIDs($project) AS $organisationID)
                $xmlOrganisations->addChild('organisation', htmlspecialchars($organisationID));

            $xmlTags = $xmlProject->addChild('tags');
            foreach ($this->getTags($project) AS $tagID)
                $xmlTags->addChild('tag', htmlspecialchars($tagID));

            $xmlTags = $xmlProject->addChild('networkTags');
            foreach ($this->getNetworkTags($project) AS $tagID)
                $xmlTags->addChild('tag', htmlspecialchars($tagID));

            $xmlTags = $xmlProject->addChild('supports');
            foreach ($this->getSupportsTags($project) AS $tagID)
                $xmlTags->addChild('tag', htmlspecialchars($tagID));

            $xmlTags = $xmlProject->addChild('focus');
            foreach ($this->getFocusTags($project) AS $tagID)
                $xmlTags->addChild('tag', htmlspecialchars($tagID));

            $xmlTags = $xmlProject->addChild('technology');
            foreach ($this->getTechnologyTags($project) AS $tagID)
                $xmlTags->addChild('tag', htmlspecialchars($tagID));
        }

        header('Content-type: text/xml');
        print($xml->asXML());
    }

    private function getProjectOrganisationIDs(Project $project)
    {
        return (new OrganisationProjectRepository())->getOrganisationIDsForProject($project);
    }

    private function getTags(Project $project)
    {
        return (new ProjectTagRepository())->getTagNamesByProject($project);
    }

    private function getNetworkTags(Project $project)
    {
        return (new ProjectNetworkTagRepository())->getTagNamesByProject($project);
    }

    private function getSupportsTags(Project $project)
    {
        return (new ProjectImpactTagARepository())->getTagNamesByProject($project);
    }

    private function getFocusTags(Project $project)
    {
        return (new ProjectDsiFocusTagRepository())->getTagNamesByProject($project);
    }

    private function getTechnologyTags(Project $project)
    {
        return (new ProjectImpactTagCRepository())->getTagNamesByProject($project);
    }
}