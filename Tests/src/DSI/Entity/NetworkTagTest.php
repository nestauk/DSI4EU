<?php

require_once __DIR__ . '/../../../config.php';

class NetworkTagTest extends \PHPUnit_Framework_TestCase
{
    /** @var \DSI\Entity\NetworkTag */
    private $networkTag;

    public function setUp()
    {
        $this->networkTag = new \DSI\Entity\NetworkTag();
    }

    /** @test setId, getId */
    public function settingAnId_returnsTheId()
    {
        $this->networkTag->setId(1);
        $this->assertEquals(1, $this->networkTag->getId());
    }

    /** @test setId */
    public function settingAnInvalidId_throwsException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->networkTag->setId(0);
    }

    /** @test setTag, getTag*/
    public function settingAName_returnsTheName()
    {
        $name = 'php';
        $this->networkTag->setName($name);
        $this->assertEquals($name, $this->networkTag->getName());
    }
}