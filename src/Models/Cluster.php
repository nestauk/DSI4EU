<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use \Models\Relationship\ClusterLang;

class Cluster extends Model
{
    const TABLE = 'clusters';
    public $timestamps = false;
    protected $table = self::TABLE;

    const Id = 'id';

    public function getId()
    {
        return $this->{self::Id};
    }

    public function clusterLangs()
    {
        return $this->hasMany(ClusterLang::class, ClusterLang::ClusterID, self::Id);
    }

    /** @return ClusterLang */
    public function getClusterLang()
    {
        return $this->clusterLangs()->first();
    }
}