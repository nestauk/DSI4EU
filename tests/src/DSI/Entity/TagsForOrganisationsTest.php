<?php

require_once __DIR__ . '/../../../config.php';

class TagForOrganisationsTest extends \PHPUnit_Framework_TestCase
{
    /** @var \DSI\Entity\TagForOrganisations */
    private $tagForOrganisations;

    public function setUp()
    {
        $this->tagForOrganisations = new \DSI\Entity\TagForOrganisations();
    }

    /** @test setId, getId */
    public function settingAnId_returnsTheId()
    {
        $this->tagForOrganisations->setId(1);
        $this->assertEquals(1, $this->tagForOrganisations->getId());
    }

    /** @test setId */
    public function settingAnInvalidId_throwsException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->tagForOrganisations->setId(0);
    }

    /** @test setTag, getTag*/
    public function settingAName_returnsTheName()
    {
        $name = 'php';
        $this->tagForOrganisations->setName($name);
        $this->assertEquals($name, $this->tagForOrganisations->getName());
    }
}