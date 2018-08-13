<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use \Models\Relationship\ClusterLang;

class Country extends Model
{
    const TABLE = 'countries';
    public $timestamps = false;
    protected $table = self::TABLE;

    const Id = 'id';
    const Name = 'name';

    public function getId()
    {
        return $this->{self::Id};
    }

    public function getName()
    {
        return $this->{self::Name};
    }
}