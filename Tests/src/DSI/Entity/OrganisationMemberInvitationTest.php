<?php

require_once __DIR__ . '/../../../config.php';

class OrganisationMemberInvitationTest extends \PHPUnit_Framework_TestCase
{
    /** @var \DSI\Entity\OrganisationMemberInvitation */
    private $organisationMemberInvitation;

    public function setUp()
    {
        $this->organisationMemberInvitation = new \DSI\Entity\OrganisationMemberInvitation();
    }

    /** @test setId, getId */
    public function settingDetails_returnsTheDetails()
    {
        $organisation = new \DSI\Entity\Organisation();
        $organisation->setId(1);
        $member = new \DSI\Entity\User();
        $member->setId(1);

        $this->organisationMemberInvitation = new \DSI\Entity\OrganisationMemberInvitation();
        $this->organisationMemberInvitation->setOrganisation($organisation);
        $this->organisationMemberInvitation->setMember($member);

        $this->assertEquals($organisation->getId(), $this->organisationMemberInvitation->getOrganisationID());
        $this->assertEquals($organisation->getId(), $this->organisationMemberInvitation->getOrganisation()->getId());
        $this->assertEquals($member->getId(), $this->organisationMemberInvitation->getMemberID());
        $this->assertEquals($member->getId(), $this->organisationMemberInvitation->getMember()->getId());
    }
}