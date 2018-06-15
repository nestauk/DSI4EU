<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    const TABLE = 'resources';
    public $timestamps = false;
    protected $table = self::TABLE;

    const Id = 'id';
    const Image = 'image';
    const Title = 'title';
    const Description = 'description';
    const LinkUrl = 'link_url';
    const LinkText = 'link_text';
    const Clusters = 'clusters';
    const AuthorID = 'author_id';

    public function getId()
    {
        return $this->{self::Id};
    }
}