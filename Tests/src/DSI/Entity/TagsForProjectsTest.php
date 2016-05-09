<?php

require_once __DIR__ . '/../../../config.php';

class TagForProjectsTest extends \PHPUnit_Framework_TestCase
{
    /** @var \DSI\Entity\TagForProjects */
    private $tagForProjects;

    public function setUp()
    {
        $this->tagForProjects = new \DSI\Entity\TagForProjects();
    }

    /** @test setId, getId */
    public function settingAnId_returnsTheId()
    {
        $this->tagForProjects->setId(1);
        $this->assertEquals(1, $this->tagForProjects->getId());
    }

    /** @test setId */
    public function settingAnInvalidId_throwsException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->tagForProjects->setId(0);
    }

    /** @test setTag, getTag*/
    public function settingAName_returnsTheName()
    {
        $name = 'php';
        $this->tagForProjects->setName($name);
        $this->assertEquals($name, $this->tagForProjects->getName());
    }
}