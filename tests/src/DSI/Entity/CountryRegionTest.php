<?php

require_once __DIR__ . '/../../../config.php';

class CountryRegionTest extends \PHPUnit_Framework_TestCase
{
    /** @var \DSI\Entity\CountryRegion */
    private $countryRegion;

    /** @var \DSI\Entity\Country */
    private $country;

    public function setUp()
    {
        $this->country = new \DSI\Entity\Country();
        $this->country->setId(1);

        $this->countryRegion = new \DSI\Entity\CountryRegion();
    }

    /** @test setId, getId */
    public function settingAnId_returnsTheId()
    {
        $this->countryRegion->setId(1);
        $this->assertEquals(1, $this->countryRegion->getId());
    }

    /** @test setId */
    public function settingAnInvalidId_throwsException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->countryRegion->setId(0);
    }

    /** @test setName, getName */
    public function settingAName_returnsTheName()
    {
        $name = 'Iasi';
        $this->countryRegion->setName($name);
        $this->assertEquals($name, $this->countryRegion->getName());
    }

    /** @test setCountry, getCountry */
    public function settingACountry_returnsTheCountry()
    {
        $this->countryRegion->setCountry($this->country);
        $this->assertEquals($this->country->getId(), $this->countryRegion->getCountry()->getId());
    }
}