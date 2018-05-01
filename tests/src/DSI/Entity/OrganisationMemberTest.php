<?php

require_once __DIR__ . '/../../../config.php';

class OrganisationMemberTest extends \PHPUnit_Framework_TestCase
{
    /** @var \DSI\Entity\OrganisationMember */
    private $organisationMember;

    public function setUp()
    {
        $this->organisationMember = new \DSI\Entity\OrganisationMember();
    }

    /** @test setId, getId */
    public function settingDetails_returnsTheDetails()
    {
        $organisation = new \DSI\Entity\Organisation();
        $organisation->setId(1);
        $member = new \DSI\Entity\User();
        $member->setId(1);

        $this->organisationMember = new \DSI\Entity\OrganisationMember();
        $this->organisationMember->setOrganisation($organisation);
        $this->organisationMember->setMember($member);

        $this->assertEquals($organisation->getId(), $this->organisationMember->getOrganisationID());
        $this->assertEquals($organisation->getId(), $this->organisationMember->getOrganisation()->getId());
        $this->assertEquals($member->getId(), $this->organisationMember->getMemberID());
        $this->assertEquals($member->getId(), $this->organisationMember->getMember()->getId());
    }
}