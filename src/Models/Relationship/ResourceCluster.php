<?php

namespace Models\Relationship;

use Illuminate\Database\Eloquent\Model;
use Models\Cluster;
use Models\Resource;

class ResourceCluster extends Model
{
    const TABLE = 'resource_clusters';
    public $timestamps = false;
    protected $table = self::TABLE;

    const ResourceID = 'resourceID';
    const ClusterID = 'clusterID';

    public function cluster()
    {
        return $this->hasOne(Cluster::class, Cluster::Id, self::ClusterID);
    }

    public function resource()
    {
        return $this->hasOne(Resource::class, Resource::Id, self::ResourceID);
    }

    public function getClusterId()
    {
        return $this->{self::ClusterID};
    }

    public function getCluster()
    {
        return $this->cluster;
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