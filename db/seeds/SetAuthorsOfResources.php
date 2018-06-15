<?php
require_once __DIR__ . '/../../src/include.php';

use Phinx\Seed\AbstractSeed;
use \Models\AuthorOfResource;

class SetAuthorsOfResources extends AbstractSeed
{
    public function run()
    {
        $authors = [
            [AuthorOfResource::Name => 'DSI4EU'],
            [AuthorOfResource::Name => 'Other'],
        ];
        foreach ($authors AS $author) {
            /** @var \Models\Tag $tag */
            $object = new AuthorOfResource();
            $object->{AuthorOfResource::Name} = $author[AuthorOfResource::Name];
            $object->save();
        }
    }
}
