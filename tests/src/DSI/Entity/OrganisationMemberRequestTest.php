<?php

require_once __DIR__ . '/../../../config.php';

class OrganisationMemberRequestTest extends \PHPUnit_Framework_TestCase
{
    /** @var \DSI\Entity\OrganisationMemberRequest */
    private $organisationMemberRequest;

    public function setUp()
    {
        $this->organisationMemberRequest = new \DSI\Entity\OrganisationMemberRequest();
    }

    /** @test setId, getId */
    public function settingDetails_returnsTheDetails()
    {
        $organisation = new \DSI\Entity\Organisation();
        $organisation->setId(1);
        $member = new \DSI\Entity\User();
        $member->setId(1);

        $this->organisationMemberRequest = new \DSI\Entity\OrganisationMemberRequest();
        $this->organisationMemberRequest->setOrganisation($organisation);
        $this->organisationMemberRequest->setMember($member);

        $this->assertEquals($organisation->getId(), $this->organisationMemberRequest->getOrganisationID());
        $this->assertEquals($organisation->getId(), $this->organisationMemberRequest->getOrganisation()->getId());
        $this->assertEquals($member->getId(), $this->organisationMemberRequest->getMemberID());
        $this->assertEquals($member->getId(), $this->organisationMemberRequest->getMember()->getId());
    }
}