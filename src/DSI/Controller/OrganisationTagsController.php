<?php

namespace DSI\Controller;

use DSI\Entity\NetworkTag;
use DSI\Entity\TagForOrganisations;
use DSI\Repository\NetworkTagRepo;
use DSI\Repository\TagForOrganisationsRepo;

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
            }, (new TagForOrganisationsRepo())->getAll()),
            'netwTags' => array_map(function (NetworkTag $tag) {
                return [
                    'id' => $tag->getId(),
                    'name' => $tag->getName(),
                ];
            }, (new NetworkTagRepo())->getAll()),
        ]);
    }
}