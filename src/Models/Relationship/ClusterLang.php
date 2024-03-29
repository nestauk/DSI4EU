<?php

namespace Models\Relationship;

use Illuminate\Database\Eloquent\Model;
use Models\Cluster;
use \Models\Relationship\ClusterImg;

class ClusterLang extends Model
{
    const TABLE = 'cluster_langs';
    public $timestamps = false;

    const Id = 'id';
    const ClusterID = 'clusterID';
    const Lang = 'lang';
    const Title = 'title';
    const Subtitle = 'subtitle';
    const Paragraph = 'paragraph';
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

    public function getId()
    {
        return $this->{self::Id};
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

    public function getParagraph()
    {
        return $this->{self::Paragraph};
    }

    public function getGetInTouch()
    {
        return $this->{self::GetInTouch};
    }

    public function getLang()
    {
        return $this->{self::Lang};
    }
}