<?php

namespace DSI\Controller;

use DSI\Entity\ImpactTag;
use DSI\Entity\TagForProjects;
use DSI\Repository\ImpactTagRepository;
use DSI\Repository\TagForProjectsRepository;

class ProjectTagsController
{
    public $responseFormat = 'json';

    public function exec()
    {
        echo json_encode([
            'tags' => array_map(function (TagForProjects $tag) {
                return [
                    'id' => $tag->getId(),
                    'name' => $tag->getName(),
                ];
            }, (new TagForProjectsRepository())->getAll()),
            'impactTags' => array_map(function (ImpactTag $tag) {
                return [
                    'id' => $tag->getId(),
                    'name' => $tag->getName(),
                ];
            }, (new ImpactTagRepository())->getAll()),
        ]);
    }
}