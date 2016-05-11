<?php

use DSI\Entity\Organisation;

require_once __DIR__ . '/../../../config.php';

class OrganisationTest extends \PHPUnit_Framework_TestCase
{
    /** @var Organisation */
    private $organisation;

    /** @var \DSI\Entity\User */
    private $owner;

    /** @var \DSI\Entity\CountryRegion */
    private $countryRegion;

    /** @var \DSI\Entity\Country */
    private $country;

    public function setUp()
    {
        $this->organisation = new Organisation();
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
    }

    /** @test setCountryRegion, getCountryRegion */
    public function settingCountryRegion_returnsCountryRegion()
    {
        $this->country = new \DSI\Entity\Country();
        $this->country->setId(1);
        $this->countryRegion = new \DSI\Entity\CountryRegion();
        $this->countryRegion->setId(2);
        $this->countryRegion->setCountry($this->country);

        $this->organisation->setCountryRegion($this->countryRegion);
        $this->assertEquals($this->countryRegion->getId(), $this->organisation->getCountryRegion()->getId());
        $this->assertEquals($this->country->getId(), $this->organisation->getCountry()->getId());
    }
}