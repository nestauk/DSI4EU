<?php

namespace Models\Relationship;

use Illuminate\Database\Eloquent\Model;

class CaseStudyTag extends Model
{
    const TABLE = 'case_study_tags';
    public $timestamps = false;
    protected $table = self::TABLE;

    const CaseStudyID = 'case_study_id';
    const TagID = 'tag_id';
}