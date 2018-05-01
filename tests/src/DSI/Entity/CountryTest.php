<?php

require_once __DIR__ . '/../../../config.php';

class CountryTest extends \PHPUnit_Framework_TestCase
{
    /** @var \DSI\Entity\Country */
    private $country;

    public function setUp()
    {
        $this->country = new \DSI\Entity\Country();
    }

    /** @test setId, getId */
    public function settingAnId_returnsTheId()
    {
        $this->country->setId(1);
        $this->assertEquals(1, $this->country->getId());
    }

    /** @test setId */
    public function settingAnInvalidId_throwsException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->country->setId(0);
    }

    /** @test setTag, getTag*/
    public function settingAName_returnsTheName()
    {
        $name = 'Romania';
        $this->country->setName($name);
        $this->assertEquals($name, $this->country->getName());
    }
}