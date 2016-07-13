<?php

use DSI\Entity\Project;

require_once __DIR__ . '/../../../config.php';

class ProjectTest extends \PHPUnit_Framework_TestCase
{
    /** @var Project */
    private $project;

    /** @var \DSI\Entity\User */
    private $owner;

    /** @var \DSI\Entity\CountryRegion */
    private $countryRegion;

    /** @var \DSI\Entity\Country */
    private $country;

    public function setUp()
    {
        $this->project = new Project();
        $this->owner = new \DSI\Entity\User();
        $this->owner->setId(1);
    }

    /** @test setId, getId */
    public function settingAnId_returnsTheId()
    {
        $this->project->setId(1);
        $this->assertEquals(1, $this->project->getId());
    }

    /** @test setId */
    public function settingAnInvalidId_throwsException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->project->setId(0);
    }

    /** @test setName, getName */
    public function settingName_returnsName()
    {
        $projectName = 'Brand New Project';
        $this->project->setName($projectName);
        $this->assertEquals($projectName, $this->project->getName());
    }

    /** @test setShortDescription, getShortDescription */
    public function settingShortDescription_returnsShortDescription()
    {
        $shortDesc = 'Brand New Project Desc';
        $this->project->setShortDescription($shortDesc);
        $this->assertEquals($shortDesc, $this->project->getShortDescription());
    }

    /** @test setDescription, getDescription */
    public function settingDescription_returnsDescription()
    {
        $projectDesc = 'Brand New Project Desc';
        $this->project->setDescription($projectDesc);
        $this->assertEquals($projectDesc, $this->project->getDescription());
    }

    /** @test setUrl, getUrl */
    public function settingUrl_returnsUrl()
    {
        $projectUrl = 'http://example.org';
        $this->project->setUrl($projectUrl);
        $this->assertEquals($projectUrl, $this->project->getUrl());
    }

    /** @test setStatus, getStatus */
    public function settingStatus_returnsStatus()
    {
        $status = 'closed';
        $this->project->setStatus($status);
        $this->assertEquals($status, $this->project->getStatus());
    }

    /** @test setStartDate, getStartDate */
    public function settingStartDate_returnsStartDate()
    {
        $startDate = '2016-05-21';
        $this->project->setStartDate($startDate);
        $this->assertEquals($startDate, $this->project->getStartDate());
    }

    /** @test setStartDate, getStartDate */
    public function settingNullStartDate_returnsStartDate()
    {
        $startDate = null;
        $this->project->setStartDate($startDate);
        $this->assertEquals($startDate, $this->project->getStartDate());
    }

    /** @test setEndDate, getEndDate */
    public function settingEndDate_returnsEndDate()
    {
        $EndDate = '2016-05-21';
        $this->project->setEndDate($EndDate);
        $this->assertEquals($EndDate, $this->project->getEndDate());
    }

    /** @test setEndDate, getEndDate */
    public function settingNullEndDate_returnsEndDate()
    {
        $EndDate = null;
        $this->project->setEndDate($EndDate);
        $this->assertEquals($EndDate, $this->project->getEndDate());
    }

    /** @test setOwner, getOwner */
    public function settingOwner_returnsOwner()
    {
        $this->project->setOwner($this->owner);
        $this->assertEquals($this->owner->getId(), $this->project->getOwner()->getId());
    }

    /** @test setOrganisationsCount, getOrganisationsCount */
    public function settingOrganisationsCount_returnsOrganisationsCount()
    {
        $this->project->setOrganisationsCount(10);
        $this->assertEquals(10, $this->project->getOrganisationsCount());
    }

    /** @test setCountryRegion, getCountryRegion */
    public function settingCountryRegion_returnsCountryRegion()
    {
        $this->country = new \DSI\Entity\Country();
        $this->country->setId(1);
        $this->countryRegion = new \DSI\Entity\CountryRegion();
        $this->countryRegion->setId(2);
        $this->countryRegion->setCountry($this->country);

        $this->project->setCountryRegion($this->countryRegion);
        $this->assertEquals($this->countryRegion->getId(), $this->project->getCountryRegion()->getId());
        $this->assertEquals($this->country->getId(), $this->project->getCountry()->getId());
    }

    /** @test */
    public function settingLogo_returnsLogo()
    {
        $logo = 'DCS100.JPG';
        $this->project->setLogo($logo);
        $this->assertEquals($logo, $this->project->getLogo());
    }

    /** @test */
    public function settingSocialImpact_returnsSocialImpact()
    {
        $socialImpact = 'Social Impact of the project';
        $this->project->setSocialImpact($socialImpact);
        $this->assertEquals($socialImpact, $this->project->getSocialImpact());
    }

    /** @test */
    public function settingIsPublished_returnsIsPublished()
    {
        $this->assertEquals(false, $this->project->isPublished());

        $this->project->setIsPublished(true);
        $this->assertEquals(true, $this->project->isPublished());

        $this->project->setIsPublished(false);
        $this->assertEquals(false, $this->project->isPublished());
    }
}