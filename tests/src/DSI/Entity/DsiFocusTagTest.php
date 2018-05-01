<?php

require_once __DIR__ . '/../../../config.php';

class DsiFocusTagTest extends \PHPUnit_Framework_TestCase
{
    /** @var \DSI\Entity\DsiFocusTag */
    private $dsiFocusTag;

    public function setUp()
    {
        $this->dsiFocusTag = new \DSI\Entity\DsiFocusTag();
    }

    /** @test setId, getId */
    public function settingAnId_returnsTheId()
    {
        $this->dsiFocusTag->setId(1);
        $this->assertEquals(1, $this->dsiFocusTag->getId());
    }

    /** @test setId */
    public function settingAnInvalidId_throwsException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->dsiFocusTag->setId(0);
    }

    /** @test setTag, getTag*/
    public function settingAName_returnsTheName()
    {
        $name = 'php';
        $this->dsiFocusTag->setName($name);
        $this->assertEquals($name, $this->dsiFocusTag->getName());
    }
}