<?php

use DSI\Entity\Organisation;
use DSI\Entity\OrganisationType;

require_once __DIR__ . '/../../../config.php';

class OrganisationTest extends \PHPUnit_Framework_TestCase
{
    /** @var Organisation */
    private $organisation;

    /** @var OrganisationType */
    private $organisationType;

    /** @var \DSI\Entity\OrganisationSize */
    private $organisationSize;

    /** @var \DSI\Entity\User */
    private $owner;

    /** @var \DSI\Entity\CountryRegion */
    private $countryRegion;

    /** @var \DSI\Entity\Country */
    private $country;

    public function setUp()
    {
        $this->organisation = new Organisation();

        $this->organisationType = new OrganisationType();
        $this->organisationType->setId(1);
        $this->organisationType->setName('Type Name');

        $this->organisationSize = new \DSI\Entity\OrganisationSize();
        $this->organisationSize->setId(1);
        $this->organisationSize->setName('Size Name');

        $this->owner = new \DSI\Entity\User();
        $this->owner->setId(1);
    }

    /** @test setId, getId */
    public function settingAnId_returnsTheId()
    {
        $this->organisation->setId(1);
        $this->assertEquals(1, $this->organisation->getId());
    }

    /** @test setId */
    public function settingAnInvalidId_throwsException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->organisation->setId(0);
    }

    /** @test setName, getName */
    public function settingName_returnsName()
    {
        $orgName = 'Brand New Organisation';
        $this->organisation->setName($orgName);
        $this->assertEquals($orgName, $this->organisation->getName());
    }

    /** @test setDescription, getDescription */
    public function settingDescription_returnsDescription()
    {
        $orgDesc = 'Brand New Organisation Desc';
        $this->organisation->setDescription($orgDesc);
        $this->assertEquals($orgDesc, $this->organisation->getDescription());
    }

    /** @test setOwner, getOwner */
    public function settingOwner_returnsOwner()
    {
        $this->organisation->setOwner($this->owner);
        $this->assertEquals($this->owner->getId(), $this->organisation->getOwner()->getId());
        $this->assertEquals($this->owner->getId(), $this->organisation->getOwnerID());
    }

    /** @test setCountryRegion, getCountryRegion */
    public function settingCountryRegion_returnsCountryRegion()
    {
        $this->country = new \DSI\Entity\Country();
        $this->country->setId(1);
        $this->country->setName($countryName = 'Romania');
        $this->countryRegion = new \DSI\Entity\CountryRegion();
        $this->countryRegion->setId(2);
        $this->countryRegion->setName($regionName = 'Iasi');
        $this->countryRegion->setCountry($this->country);

        $this->organisation->setCountryRegion($this->countryRegion);
        $this->assertEquals($this->countryRegion->getId(), $this->organisation->getRegion()->getId());
        $this->assertEquals($this->countryRegion->getId(), $this->organisation->getCountryRegionID());
        $this->assertEquals($this->country->getName(), $this->organisation->getCountryName());
        $this->assertEquals($this->country->getId(), $this->organisation->getCountry()->getId());
        $this->assertEquals($this->country->getId(), $this->organisation->getCountryID());
    }

    /** @test setOrganisationType, getOrganisationType */
    public function settingOrgType_returnsOrgType()
    {
        $this->organisation->setType($this->organisationType);
        $this->assertEquals($this->organisationType->getId(), $this->organisation->getType()->getId());
        $this->assertEquals($this->organisationType->getId(), $this->organisation->getTypeId());
        $this->assertEquals($this->organisationType->getName(), $this->organisation->getTypeName());
    }

    /** @test setOrganisationSize, getOrganisationSize */
    public function settingOrgSize_returnsOrgSize()
    {
        $this->organisation->setSize($this->organisationSize);
        $this->assertEquals($this->organisationSize->getId(), $this->organisation->getSize()->getId());
        $this->assertEquals($this->organisationSize->getId(), $this->organisation->getSizeId());
        $this->assertEquals($this->organisationSize->getName(), $this->organisation->getSizeName());
    }

    /** @test setAddress, getAddress */
    public function settingAddress_returnsAddress()
    {
        $this->organisation->setAddress('test');
        $this->assertEquals('test', $this->organisation->getAddress());
    }

    /** @test */
    public function settingPartnersCount_returnsPartnersCount()
    {
        $partnersCount = 10;
        $this->organisation->setPartnersCount($partnersCount);
        $this->assertEquals($partnersCount, $this->organisation->getPartnersCount());
    }

    /** @test */
    public function settingProjectsCount_returnsProjectsCount()
    {
        $projectsCount = 10;
        $this->organisation->setProjectsCount($projectsCount);
        $this->assertEquals($projectsCount, $this->organisation->getProjectsCount());
    }

    /** @test */
    public function settingEmptyUrl_returnsEmptyUrl()
    {
        $url = '';
        $this->organisation->setUrl($url);
        $this->assertEquals($url, $this->organisation->getUrl());
        $this->assertEquals($url, $this->organisation->getExternalUrl());
    }

    /** @test */
    public function settingUrl_returnsUrl()
    {
        $url = 'http://example.org';
        $this->organisation->setUrl($url);
        $this->assertEquals($url, $this->organisation->getUrl());
    }

    /** @test */
    public function settingInvalidExternalUrl_returnsValidExternalUrl()
    {
        $url = 'www.example.org';
        $this->organisation->setUrl($url);
        $this->assertEquals('http://' . $url, $this->organisation->getExternalUrl());
    }

    /** @test */
    public function settingShortDesc_returnsShortDesc()
    {
        $shortDesc = 'Short Description';
        $this->organisation->setShortDescription($shortDesc);
        $this->assertEquals($shortDesc, $this->organisation->getShortDescription());
    }

    /** @test */
    public function settingStartDate_returnsStartDate()
    {
        $shortDesc = 'Start Date';
        $this->organisation->setStartDate($shortDesc);
        $this->assertEquals($shortDesc, $this->organisation->getStartDate());
    }

    /** @test */
    public function settingLogo_returnsLogo()
    {
        $logo = 'DSC100.jpg';
        $this->organisation->setLogo($logo);
        $this->assertEquals($logo, $this->organisation->getLogo());
    }

    /** @test */
    public function settingHeaderImage_returnsHeaderImage()
    {
        $headerImage = 'DSC100.jpg';
        $this->organisation->setHeaderImage($headerImage);
        $this->assertEquals($headerImage, $this->organisation->getHeaderImage());
    }

    /** @test */
    public function settingImportId_returnsImportId()
    {
        $this->assertEquals('', $this->organisation->getImportID());
        $importID = '3245342ABC';
        $this->organisation->setImportID($importID);
        $this->assertEquals($importID, $this->organisation->getImportID());
    }
}