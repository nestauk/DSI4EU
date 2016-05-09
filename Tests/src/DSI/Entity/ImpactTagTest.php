<?php

require_once __DIR__ . '/../../../config.php';

class ImpactTagTest extends \PHPUnit_Framework_TestCase
{
    /** @var \DSI\Entity\ImpactTag */
    private $impactTag;

    public function setUp()
    {
        $this->impactTag = new \DSI\Entity\ImpactTag();
    }

    /** @test setId, getId */
    public function settingAnId_returnsTheId()
    {
        $this->impactTag->setId(1);
        $this->assertEquals(1, $this->impactTag->getId());
    }

    /** @test setId */
    public function settingAnInvalidId_throwsException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->impactTag->setId(0);
    }

    /** @test setTag, getTag*/
    public function settingAName_returnsTheName()
    {
        $name = 'php';
        $this->impactTag->setName($name);
        $this->assertEquals($name, $this->impactTag->getName());
    }
}