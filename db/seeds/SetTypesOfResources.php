<?php

use Phinx\Seed\AbstractSeed;
use \Models\TypeOfResource;

class SetTypesOfResources extends AbstractSeed
{
    public function run()
    {
        $authors = [
            [TypeOfResource::Name => 'Type 1'],
            [TypeOfResource::Name => 'Type 2'],
            [TypeOfResource::Name => 'Type 3'],
            [TypeOfResource::Name => 'Type 4'],
        ];
        foreach ($authors AS $author) {
            $object = new TypeOfResource();
            $object->{TypeOfResource::Name} = $author[TypeOfResource::Name];
            $object->save();
        }
    }
}
