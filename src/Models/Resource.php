<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    const TABLE = 'resources';
    protected $table = self::TABLE;
    public $timestamps = false;

    const Id = 'id';
    const Image = 'image';
    const Title = 'title';
    const Description = 'description';
    const LinkUrl = 'link_url';
    const LinkText = 'link_text';

    public function getId()
    {
        return $this->{self::Id};
    }
}