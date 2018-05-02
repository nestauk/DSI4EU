<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    const TABLE = 'clusters';
    public $timestamps = false;

    const Id = 'id';

    public function getId()
    {
        return $this->{self::Id};
    }
}