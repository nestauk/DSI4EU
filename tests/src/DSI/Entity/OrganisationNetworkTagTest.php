<?php

require_once __DIR__ . '/../../../config.php';

class OrganisationNetworkTagTest extends \PHPUnit_Framework_TestCase
{
    /** @var \DSI\Entity\OrganisationNetworkTag */
    private $organisationNetworkTag;

    public function setUp()
    {
        $this->organisationNetworkTag = new \DSI\Entity\OrganisationNetworkTag();
    }

    /** @test setId, getId */
    public function settingDetails_returnsTheDetails()
    {
        $organisation = new \DSI\Entity\Organisation();
        $organisation->setId(1);
        $tag = new \DSI\Entity\NetworkTag();
        $tag->setId(1);

        $this->organisationNetworkTag = new \DSI\Entity\OrganisationNetworkTag();
        $this->organisationNetworkTag->setOrganisation($organisation);
        $this->organisationNetworkTag->setTag($tag);

        $this->assertEquals($organisation->getId(), $this->organisationNetworkTag->getOrganisationID());
        $this->assertEquals($organisation->getId(), $this->organisationNetworkTag->getOrganisation()->getId());
        $this->assertEquals($tag->getId(), $this->organisationNetworkTag->getTagID());
        $this->assertEquals($tag->getId(), $this->organisationNetworkTag->getTag()->getId());
    }
}