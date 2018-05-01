<?php

require_once __DIR__ . '/../../../config.php';

class OrganisationFollowTest extends \PHPUnit_Framework_TestCase
{
    /** @var \DSI\Entity\OrganisationFollow */
    private $organisationFollow;

    public function setUp()
    {
        $this->organisationFollow = new \DSI\Entity\OrganisationFollow();
    }

    /** @test setId, getId */
    public function settingDetails_returnsTheDetails()
    {
        $organisation = new \DSI\Entity\Organisation();
        $organisation->setId(1);
        $user = new \DSI\Entity\User();
        $user->setId(1);

        $this->organisationFollow = new \DSI\Entity\OrganisationFollow();
        $this->organisationFollow->setOrganisation($organisation);
        $this->organisationFollow->setUser($user);

        $this->assertEquals($organisation->getId(), $this->organisationFollow->getOrganisationID());
        $this->assertEquals($organisation->getId(), $this->organisationFollow->getOrganisation()->getId());
        $this->assertEquals($user->getId(), $this->organisationFollow->getUserID());
        $this->assertEquals($user->getId(), $this->organisationFollow->getUser()->getId());
    }
}