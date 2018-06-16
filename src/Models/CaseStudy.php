<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use \Models\Relationship\ClusterLang;

class CaseStudy extends Model
{
    const TABLE = 'case-studies';
    public $timestamps = false;
    protected $table = self::TABLE;

    const Id = 'id';
    const Title = 'title';
    const IntroCardText = 'introCardText';
    const IntroPageText = 'introPageText';
    const InfoText = 'infoText';
    const MainText = 'mainText';
    const ProjectStartDate = 'projectStartDate';
    const ProjectEndDate = 'projectEndDate';
    const Url = 'url';
    const ButtonLabel = 'buttonLabel';
    const Logo = 'logo';
    const CardImage = 'cardImage';
    const HeaderImage = 'headerImage';
    const CardColour = 'cardColour';
    const IsPublished = 'isPublished';
    const IsFeaturedOnSlider = 'isFeaturedOnSlider';
    const IsFeaturedOnHomePage = 'isFeaturedOnHomePage';
    const RegionID = 'regionID';
    const ProjectID = 'projectID';
    const OrganisationID = 'organisationID';

    public function getId()
    {
        return $this->{self::Id};
    }
}