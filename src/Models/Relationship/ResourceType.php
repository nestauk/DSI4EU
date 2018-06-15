<?php

namespace Models\Relationship;

use Illuminate\Database\Eloquent\Model;
use Models\TypeOfResource;
use Models\Resource;

class ResourceType extends Model
{
    const TABLE = 'resource_types';
    public $timestamps = false;
    protected $table = self::TABLE;

    const ResourceID = 'resource_id';
    const TypeID = 'type_id';

    public function type()
    {
        return $this->hasOne(TypeOfResource::class, TypeOfResource::Id, self::TypeID);
    }

    public function resource()
    {
        return $this->hasOne(Resource::class, Resource::Id, self::ResourceID);
    }

    public function getTypeId()
    {
        return $this->{self::TypeID};
    }

    public function getType()
    {
        return $this->type;
    }

    public function getResourceId()
    {
        return $this->{self::ResourceID};
    }

    public function getResource()
    {
        return $this->resource;
    }
}