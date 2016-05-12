<?php

require_once __DIR__ . '/../../../config.php';

class OrganisationTagTest extends \PHPUnit_Framework_TestCase
{
    /** @var \DSI\Entity\OrganisationTag */
    private $organisationTag;

    public function setUp()
    {
        $this->organisationTag = new \DSI\Entity\OrganisationTag();
    }

    /** @test setId, getId */
    public function settingDetails_returnsTheDetails()
    {
        $organisation = new \DSI\Entity\Organisation();
        $organisation->setId(1);
        $tag = new \DSI\Entity\TagForOrganisations();
        $tag->setId(1);

        $this->organisationTag = new \DSI\Entity\OrganisationTag();
        $this->organisationTag->setOrganisation($organisation);
        $this->organisationTag->setTag($tag);

        $this->assertEquals($organisation->getId(), $this->organisationTag->getOrganisationID());
        $this->assertEquals($organisation->getId(), $this->organisationTag->getOrganisation()->getId());
        $this->assertEquals($tag->getId(), $this->organisationTag->getTagID());
        $this->assertEquals($tag->getId(), $this->organisationTag->getTag()->getId());
    }
}