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
        $this->assertEquals('21-05-2016', $this->project->getStartDate('d-m-Y'));
    }

    /** @test */
    public function startDateHasPassedOrInFuture()
    {
        $startDate = date('Y-m-d', time() - 60 * 60 * 24 * 5);
        $this->project->setStartDate($startDate);
        $this->assertEquals(true, $this->project->startDateHasPassed());
        $this->assertEquals(false, $this->project->startDateIsInFuture());

        $startDate = date('Y-m-d', time() + 60 * 60 * 24 * 5);
        $this->project->setStartDate($startDate);
        $this->assertEquals(false, $this->project->startDateHasPassed());
        $this->assertEquals(true, $this->project->startDateIsInFuture());
    }

    /** @test */
    public function endDateHasPassed()
    {
        $endDate = date('Y-m-d', time() - 60 * 60 * 24 * 5);
        $this->project->setEndDate($endDate);
        $this->assertEquals(true, $this->project->endDateHasPassed());

        $endDate = date('Y-m-d', time() + 60 * 60 * 24 * 5);
        $this->project->setEndDate($endDate);
        $this->assertEquals(false, $this->project->endDateHasPassed());
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
        $endDate = '2016-05-21';
        $this->project->setEndDate($endDate);
        $this->assertEquals($endDate, $this->project->getEndDate());
        $this->assertEquals('21-05-2016', $this->project->getEndDate('d-m-Y'));
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
        $this->assertEquals($this->countryRegion->getId(), $this->project->getRegion()->getId());
        $this->assertEquals($this->countryRegion->getName(), $this->project->getRegionName());
        $this->assertEquals($this->country->getId(), $this->project->getCountry()->getId());
        $this->assertEquals($this->country->getName(), $this->project->getCountryName());
    }

    /** @test */
    public function settingLogo_returnsLogo()
    {
        $this->assertEquals('', $this->project->getLogo());

        $logo = 'DCS100.JPG';
        $this->project->setLogo($logo);
        $this->assertEquals($logo, $this->project->getLogo());
    }

    /** @test */
    public function settingLogo_returnsLogoOrDefault()
    {
        $this->assertEquals(Project::DEFAULT_LOGO, $this->project->getLogoOrDefault());
        $this->assertEquals(Project::DEFAULT_LOGO_SILVER, $this->project->getLogoOrDefaultSilver());

        $logo = 'DCS100.JPG';
        $this->project->setLogo($logo);
        $this->assertEquals($logo, $this->project->getLogoOrDefault());
        $this->assertEquals($logo, $this->project->getLogoOrDefaultSilver());
    }

    /** @test */
    public function settingHeaderImage_returnsHeaderImage()
    {
        $this->assertEquals('', $this->project->getHeaderImage());

        $headerImage = 'DCS100.JPG';
        $this->project->setHeaderImage($headerImage);
        $this->assertEquals($headerImage, $this->project->getHeaderImage());
    }

    /** @test */
    public function settingHeaderImage_returnsHeaderImageOrDefault()
    {
        $this->assertEquals(
            Project::DEFAULT_HEADER_IMAGE,
            $this->project->getHeaderImageOrDefault()
        );

        $headerImage = 'DCS100.JPG';
        $this->project->setHeaderImage($headerImage);
        $this->assertEquals($headerImage, $this->project->getHeaderImageOrDefault());
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