<?php

namespace DSI\Controller\CLI;

use DSI\NotFound;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\ProjectImpactHelpTagRepository;
use DSI\Repository\ProjectImpactTechTagRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\ProjectRepositoryInAPC;
use DSI\UseCase\AddImpactHelpTagToProject;
use DSI\UseCase\AddImpactTechTagToProject;
use DSI\UseCase\CalculateOrganisationPartnersCount;
use DSI\UseCase\RemoveImpactHelpTagFromProject;
use DSI\UseCase\RemoveImpactTechTagFromProject;

class UpdateProjectTechTagsController
{
    /** @var  String[] */
    private $args;

    public function exec()
    {
        if (!isset($this->args[2]))
            throw new \Exception('Provide file path');

        $filename = basename($this->args[2]);

        $filePath = __DIR__ . '/../../../../' . $filename;
        if (!file_exists($filePath))
            throw new NotFound($filePath);

        $this->updateTagsFromFile($filePath);
    }

    /**
     * @param \String[] $args
     */
    public function setArgs(array $args)
    {
        $this->args = $args;
    }

    /**
     * @param $filePath
     */
    private function updateTagsFromFile($filePath)
    {
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            while (($data = fgetcsv($handle)) !== FALSE) {
                $projectID = (int)$data[0];
                $techTags = explode(';', $data[2]);
                $techTags = array_map('trim', $techTags);
                $techTags = array_filter($techTags);
                $techTags = array_unique($techTags);
                $techTagsLowerCase = array_map('strtolower', $techTags);

                try {
                    $project = (new ProjectRepositoryInAPC())->getById($projectID);
                } catch (NotFound $e) {
                    echo "project id {$projectID} not found" . PHP_EOL;
                    continue;
                }

                $projectTechTags = (new ProjectImpactTechTagRepository())->getTagNamesByProject($project);
                $projectTechTagsLowerCase = array_map('strtolower', $projectTechTags);

                foreach ($projectTechTags AS $oldTagName) {
                    if (!in_array(strtolower($oldTagName), $techTagsLowerCase)) {
                        echo "remove $oldTagName" . PHP_EOL;
                        $remTag = new RemoveImpactTechTagFromProject();
                        $remTag->data()->projectID = $projectID;
                        $remTag->data()->tag = $oldTagName;
                        $remTag->exec();
                    }
                }
                foreach ($techTags AS $newTagName) {
                    if (!in_array(strtolower($newTagName), $projectTechTagsLowerCase)) {
                        echo "add $newTagName" . PHP_EOL;
                        $addTag = new AddImpactTechTagToProject();
                        $addTag->data()->projectID = $projectID;
                        $addTag->data()->tag = $newTagName;
                        $addTag->exec();
                    }
                }
            }

            fclose($handle);
        }
    }
}