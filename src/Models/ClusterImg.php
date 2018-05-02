<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class ClusterImg extends Model
{
    const TABLE = 'cluster_imgs';
    public $timestamps = false;

    const Id = 'id';
    const ClusterLangID = 'clusterLangID';
    const Filename = 'filename';
    const Link = 'link';
}