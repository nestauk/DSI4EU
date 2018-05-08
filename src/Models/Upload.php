<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    const TABLE = 'uploads';
    public $timestamps = false;

    const Id = 'id';
    const Filename = 'filename';

    public function getId()
    {
        return $this->{self::Id};
    }
}