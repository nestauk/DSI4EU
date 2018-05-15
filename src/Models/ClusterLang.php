<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class ClusterLang extends Model
{
    const TABLE = 'cluster_langs';
    public $timestamps = false;

    const Id = 'id';
    const ClusterID = 'clusterID';
    const Lang = 'lang';
    const Title = 'title';
    const Subtitle = 'subtitle';
    const Description = 'description';
    const GetInTouch = 'get_in_touch';

    public function cluster()
    {
        return $this->hasOne(Cluster::class, Cluster::Id, self::ClusterID);
    }

    public function images()
    {
        return $this->hasMany(ClusterImg::class, ClusterImg::ClusterLangID, self::Id);
    }

    public function getClusterId()
    {
        return $this->{self::ClusterID};
    }

    public function getCluster()
    {
        return $this->cluster;
    }

    /** @return ClusterImg[] */
    public function getImages()
    {
        return $this->images;
    }

    public function getTitle()
    {
        return $this->{self::Title};
    }

    public function getSubtitle()
    {
        return $this->{self::Subtitle};
    }

    public function getDescription()
    {
        return $this->{self::Description};
    }

    public function getGetInTouch()
    {
        return $this->{self::GetInTouch};
    }
}