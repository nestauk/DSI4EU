<?php

namespace DSI\Controller;

use DSI\Entity\Project;
use DSI\Repository\OrganisationProjectRepository;
use DSI\Repository\ProjectDsiFocusTagRepository;
use DSI\Repository\ProjectImpactHelpTagRepository;
use DSI\Repository\ProjectImpactTechTagRepository;
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
                'project_id' => $project->getId(),
                'project_name' => $project->getName(),
                'website' => $project->getExternalUrl(),
                'short_description' => $project->getShortDescription(),
                'long_description' => $project->getDescription(),
                'social_impact' => $project->getSocialImpact(),
                'start_date' => $project->getStartDate(),
                'end_date' => $project->getEndDate(),
                'country' => $project->getCountryName(),
                'region' => $project->getRegionName(),
                'linked_organisation_ids' => $this->getProjectOrganisationIDs($project),
                'who_we_help_tags' => $this->getTags($project),
                'support_tags' => $this->getSupportsTags($project),
                'focus' => $this->getFocusTags($project),
                'technology' => $this->getTechnologyTags($project),
                'creation_date' => $project->getCreationTime('Y-m-d'),
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
            'Project Id',
            'Project name',
            'Website',
            'Short description',
            'Long description',
            'Social impact',
            'Start date',
            'End date',
            'Country',
            'Region',
            'Linked Organisation IDs',
            'Who we help tags',
            'Support tags',
            'Focus',
            'Technology',
            'Creation date',
        ]);

        foreach ($projects as $project) {
            fputcsv($fp, [
                'project_id' => $project->getId(),
                'project_name' => $project->getName(),
                'website' => $project->getExternalUrl(),
                'short_description' => $project->getShortDescription(),
                'long_description' => $project->getDescription(),
                'social_impact' => $project->getSocialImpact(),
                'start_date' => $project->getStartDate(),
                'end_date' => $project->getEndDate(),
                'country' => $project->getCountryName(),
                'region' => $project->getRegionName(),
                'linked_organisation_ids' => implode(', ', $this->getProjectOrganisationIDs($project)),
                'who_we_help_tags' => implode(', ', $this->getTags($project)),
                'support_tags' => implode(', ', $this->getSupportsTags($project)),
                'focus' => implode(', ', $this->getFocusTags($project)),
                'technology' => implode(', ', $this->getTechnologyTags($project)),
                'creation_date' => $project->getCreationTime('Y-m-d'),
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
            $xmlProject->addChild('project_id', $project->getId());
            $xmlProject->addChild('project_name', htmlspecialchars($project->getName()));
            $xmlProject->addChild('website', htmlspecialchars($project->getExternalUrl()));
            $xmlProject->addChild('short_description', htmlspecialchars($project->getShortDescription()));
            $xmlProject->addChild('long_description', htmlspecialchars($project->getDescription()));
            $xmlProject->addChild('social_impact', htmlspecialchars($project->getSocialImpact()));
            $xmlProject->addChild('start_date', htmlspecialchars($project->getStartDate()));
            $xmlProject->addChild('end_date', htmlspecialchars($project->getEndDate()));
            $xmlProject->addChild('country', htmlspecialchars($project->getCountryName()));
            $xmlProject->addChild('region', htmlspecialchars($project->getRegionName()));

            $xmlOrganisations = $xmlProject->addChild('linked_organisation_ids');
            foreach ($this->getProjectOrganisationIDs($project) AS $organisationID)
                $xmlOrganisations->addChild('organisation', htmlspecialchars($organisationID));

            $xmlTags = $xmlProject->addChild('who_we_help_tags');
            foreach ($this->getTags($project) AS $tagID)
                $xmlTags->addChild('tag', htmlspecialchars($tagID));

            $xmlTags = $xmlProject->addChild('support_tags');
            foreach ($this->getSupportsTags($project) AS $tagID)
                $xmlTags->addChild('tag', htmlspecialchars($tagID));

            $xmlTags = $xmlProject->addChild('focus');
            foreach ($this->getFocusTags($project) AS $tagID)
                $xmlTags->addChild('tag', htmlspecialchars($tagID));

            $xmlTags = $xmlProject->addChild('technology');
            foreach ($this->getTechnologyTags($project) AS $tagID)
                $xmlTags->addChild('tag', htmlspecialchars($tagID));

            $xmlProject->addChild('creation_date', htmlspecialchars($project->getCreationTime('Y-m-d')));
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

    private function getSupportsTags(Project $project)
    {
        return (new ProjectImpactHelpTagRepository())->getTagNamesByProject($project);
    }

    private function getFocusTags(Project $project)
    {
        return (new ProjectDsiFocusTagRepository())->getTagNamesByProject($project);
    }

    private function getTechnologyTags(Project $project)
    {
        return (new ProjectImpactTechTagRepository())->getTagNamesByProject($project);
    }
}