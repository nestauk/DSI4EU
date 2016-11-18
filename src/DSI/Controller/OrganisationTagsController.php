<?php

namespace DSI\Controller;

use DSI\Entity\NetworkTag;
use DSI\Entity\TagForOrganisations;
use DSI\Repository\NetworkTagRepository;
use DSI\Repository\TagForOrganisationsRepository;

class OrganisationTagsController
{
    public $responseFormat = 'json';

    public function exec()
    {
        echo json_encode([
            'tags' => array_map(function (TagForOrganisations $tag) {
                return [
                    'id' => $tag->getId(),
                    'name' => $tag->getName(),
                ];
            }, (new TagForOrganisationsRepository())->getAll()),
            'netwTags' => array_map(function (NetworkTag $tag) {
                return [
                    'id' => $tag->getId(),
                    'name' => $tag->getName(),
                ];
            }, (new NetworkTagRepository())->getAll()),
        ]);
    }
}