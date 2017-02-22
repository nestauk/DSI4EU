<?php

namespace DSI\Controller;

use DSI\NotFound;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\ProjectImpactHelpTagRepository;
use DSI\Repository\ProjectRepository;
use DSI\UseCase\AddImpactHelpTagToProject;
use DSI\UseCase\CalculateOrganisationPartnersCount;
use DSI\UseCase\RemoveImpactHelpTagFromProject;

class UpdateProjectTagsController
{
    /** @var  String[] */
    private $args;

    public function exec()
    {
        if (!isset($this->args[2]))
            throw new \Exception('Provide file path');

        $filename = basename($this->args[2]);

        $filePath = __DIR__ . '/../../../' . $filename;
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
                $projectID = (int)$data[1];
                $areasOfImpact = explode(',', $data[4]);
                $areasOfImpact = array_map('trim', $areasOfImpact);
                $areasOfImpact = array_filter($areasOfImpact);
                $areasOfImpact = array_unique($areasOfImpact);
                $areasOfImpactLowerCase = array_map('strtolower', $areasOfImpact);

                try {
                    $project = (new ProjectRepository())->getById($projectID);
                } catch (NotFound $e) {
                    echo "project id {$projectID} not found" . PHP_EOL;
                    continue;
                }

                $projectTags = (new ProjectImpactHelpTagRepository())->getTagNamesByProject($project);
                $projectTagsLowerCase = array_map('strtolower', $projectTags);

                foreach ($projectTags AS $oldTagName) {
                    if (!in_array(strtolower($oldTagName), $areasOfImpactLowerCase)) {
                        echo "remove $oldTagName" . PHP_EOL;
                        $remTag = new RemoveImpactHelpTagFromProject();
                        $remTag->data()->projectID = $projectID;
                        $remTag->data()->tag = $oldTagName;
                        $remTag->exec();
                    }
                }
                foreach ($areasOfImpact AS $newTagName) {
                    if (!in_array(strtolower($newTagName), $projectTagsLowerCase)) {
                        echo "add $newTagName" . PHP_EOL;
                        $addTag = new AddImpactHelpTagToProject();
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