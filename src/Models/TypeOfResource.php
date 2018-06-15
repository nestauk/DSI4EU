<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class TypeOfResource extends Model
{
    const TABLE = 'type_of_resources';
    public $timestamps = false;
    protected $table = self::TABLE;

    const Id = 'id';
    const Name = 'name';

    public function getId()
    {
        return $this->{self::Id};
    }
}