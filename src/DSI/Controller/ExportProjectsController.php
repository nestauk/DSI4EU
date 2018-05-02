<?php

namespace DSI\Controller;

use DSI\Entity\Project;
use DSI\Entity\ProjectImpactHelpTag;
use DSI\Entity\ProjectImpactTechTag;
use DSI\Repository\OrganisationProjectRepo;
use DSI\Repository\ProjectDsiFocusTagRepo;
use DSI\Repository\ProjectImpactHelpTagRepo;
use DSI\Repository\ProjectImpactTechTagRepo;
use DSI\Repository\ProjectRepoInAPC;
use DSI\Repository\ProjectTagRepo;
use DSI\Service\Auth;
use Services\URL;

class ExportProjectsController
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

        $projects = (new ProjectRepoInAPC())->getAll();

        if (isset($_GET['download'])) {
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename="dsi-projects.' . $this->format . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
        }

        if ($this->format == 'json') {
            header('Content-Type: application/json');
            $this->exportJson($projects);
        } elseif ($this->format == 'csv') {
            header('Content-Type: text/csv');
            $this->exportCsv($projects);
        } elseif ($this->format == 'xml') {
            header('Content-Type: text/xml');
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
                'latitude' => $project->getRegionLatitude(),
                'longitude' => $project->getRegionLongitude(),
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
            'Latitude',
            'Longitude',
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
                'latitude' => $project->getRegionLatitude(),
                'longitude' => $project->getRegionLongitude(),
                'linked_organisation_ids' => implode(', ', $this->getProjectOrganisationIDs($project)),
                'who_we_help_tags' => implode(', ', $this->getTags($project)),
                'support_tags' => implode(', ', $this->getSupportsTagsNames($project)),
                'focus' => implode(', ', $this->getFocusTagsNames($project)),
                'technology' => implode(', ', $this->getTechnologyTagsNames($project)),
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
            $xmlProject->addChild('latitude', htmlspecialchars($project->getRegionLatitude()));
            $xmlProject->addChild('longitude', htmlspecialchars($project->getRegionLongitude()));

            $xmlOrganisations = $xmlProject->addChild('linked_organisation_ids');
            foreach ($this->getProjectOrganisationIDs($project) AS $organisationID)
                $xmlOrganisations->addChild('organisation', htmlspecialchars($organisationID));

            $xmlTags = $xmlProject->addChild('who_we_help_tags');
            foreach ($this->getTags($project) AS $tag)
                $xmlTags->addChild('tag', htmlspecialchars($tag));

            $xmlTags = $xmlProject->addChild('support_tags');
            foreach ($this->getSupportsTags($project) AS $tag) {
                $xmlTag = $xmlTags->addChild('tag');
                $xmlTag->addChild('id', $tag['id']);
                $xmlTag->addChild('name', htmlspecialchars($tag['name']));
            }

            $xmlTags = $xmlProject->addChild('focus');
            foreach ($this->getFocusTags($project) AS $tag) {
                $xmlTag = $xmlTags->addChild('tag');
                $xmlTag->addChild('id', $tag['id']);
                $xmlTag->addChild('name', htmlspecialchars($tag['name']));
            }

            $xmlTags = $xmlProject->addChild('technology');
            foreach ($this->getTechnologyTags($project) AS $tag) {
                $xmlTag = $xmlTags->addChild('tag');
                $xmlTag->addChild('id', $tag['id']);
                $xmlTag->addChild('name', htmlspecialchars($tag['name']));
            }

            $xmlProject->addChild('creation_date', htmlspecialchars($project->getCreationTime('Y-m-d')));
        }

        header('Content-type: text/xml');
        print($xml->asXML());
    }

    private function getProjectOrganisationIDs(Project $project)
    {
        return (new OrganisationProjectRepo())->getOrganisationIDsForProject($project);
    }

    private function getTags(Project $project)
    {
        return (new ProjectTagRepo())->getTagNamesByProject($project);
    }

    private function getSupportsTagsNames(Project $project)
    {
        return (new ProjectImpactHelpTagRepo())->getTagNamesByProject($project);
    }

    private function getSupportsTags(Project $project)
    {
        return (new ProjectImpactHelpTagRepo())->getTagDataByProject($project);
    }

    private function getFocusTagsNames(Project $project)
    {
        return (new ProjectDsiFocusTagRepo())->getTagNamesByProject($project);
    }

    private function getFocusTags(Project $project)
    {
        return (new ProjectDsiFocusTagRepo())->getTagDataByProject($project);
    }

    private function getTechnologyTagsNames(Project $project)
    {
        return (new ProjectImpactTechTagRepo())->getTagNamesByProject($project);
    }

    private function getTechnologyTags(Project $project)
    {
        return (new ProjectImpactTechTagRepo())->getTagDataByProject($project);
    }
}