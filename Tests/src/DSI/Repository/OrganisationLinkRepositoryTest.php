<?php

require_once __DIR__ . '/../../../config.php';

class OrganisationLinkRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Repository\OrganisationLinkRepository */
    private $organisationLinkRep;

    /** @var \DSI\Repository\OrganisationRepository */
    private $organisationRepo;

    /** @var \DSI\Entity\Organisation */
    private $organisation_1, $organisation_2, $organisation_3;

    /** @var string */
    private $link_1, $link_2, $link_3;

    /** @var  \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user;

    public function setUp()
    {
        $this->organisationLinkRep = new \DSI\Repository\OrganisationLinkRepository();
        $this->organisationRepo = new \DSI\Repository\OrganisationRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();

        $this->user = new \DSI\Entity\User();
        $this->userRepo->insert($this->user);

        $this->organisation_1 = $this->createOrganisation(1);
        $this->organisation_2 = $this->createOrganisation(2);
        $this->organisation_3 = $this->createOrganisation(3);
        $this->link_1 = 'http://example.com/';
        $this->link_2 = 'http://google.com/';
        $this->link_3 = 'http://yahoo.com/';
    }

    public function tearDown()
    {
        $this->organisationLinkRep->clearAll();
        $this->organisationRepo->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test saveAsNew */
    public function organisationLinkCanBeSaved()
    {
        $organisationLink = new \DSI\Entity\OrganisationLink();
        $organisationLink->setOrganisation($this->organisation_1);
        $organisationLink->setLink($this->link_1);
        $this->organisationLinkRep->add($organisationLink);

        $organisationLink = new \DSI\Entity\OrganisationLink();
        $organisationLink->setOrganisation($this->organisation_1);
        $organisationLink->setLink($this->link_2);
        $this->organisationLinkRep->add($organisationLink);

        $organisationLink = new \DSI\Entity\OrganisationLink();
        $organisationLink->setOrganisation($this->organisation_2);
        $organisationLink->setLink($this->link_1);
        $this->organisationLinkRep->add($organisationLink);

        $organisationLink = new \DSI\Entity\OrganisationLink();
        $organisationLink->setOrganisation($this->organisation_3);
        $organisationLink->setLink($this->link_1);
        $this->organisationLinkRep->add($organisationLink);

        $this->assertCount(2, $this->organisationLinkRep->getByOrganisationID(1));
    }

    /** @test */
    public function cannotAddSameOrganisationLinkTwice()
    {
        $organisationLink = new \DSI\Entity\OrganisationLink();
        $organisationLink->setOrganisation($this->organisation_1);
        $organisationLink->setLink($this->link_1);
        $this->organisationLinkRep->add($organisationLink);

        $organisationLink = new \DSI\Entity\OrganisationLink();
        $organisationLink->setOrganisation($this->organisation_1);
        $organisationLink->setLink($this->link_1);
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->organisationLinkRep->add($organisationLink);
    }

    /** @test saveAsNew */
    public function canCheckIfOrganisationHasLink()
    {
        $organisationLink = new \DSI\Entity\OrganisationLink();
        $organisationLink->setOrganisation($this->organisation_1);
        $organisationLink->setLink($this->link_1);
        $this->organisationLinkRep->add($organisationLink);

        $organisationLink = new \DSI\Entity\OrganisationLink();
        $organisationLink->setOrganisation($this->organisation_2);
        $organisationLink->setLink($this->link_2);
        $this->organisationLinkRep->add($organisationLink);

        $this->assertTrue($this->organisationLinkRep->organisationHasLink(
            $this->organisation_1->getId(), $this->link_1)
        );
        $this->assertFalse($this->organisationLinkRep->organisationHasLink(
            $this->organisation_1->getId(), $this->link_2)
        );
        $this->assertTrue($this->organisationLinkRep->organisationHasLink(
            $this->organisation_2->getId(), $this->link_2)
        );
        $this->assertFalse($this->organisationLinkRep->organisationHasLink(
            $this->organisation_2->getId(), $this->link_1)
        );
    }

    /** @test saveAsNew */
    public function canGetLinksByOrganisationID()
    {
        $organisationLink = new \DSI\Entity\OrganisationLink();
        $organisationLink->setOrganisation($this->organisation_1);
        $organisationLink->setLink($this->link_1);
        $this->organisationLinkRep->add($organisationLink);

        $organisationLink = new \DSI\Entity\OrganisationLink();
        $organisationLink->setOrganisation($this->organisation_1);
        $organisationLink->setLink($this->link_2);
        $this->organisationLinkRep->add($organisationLink);

        $organisationLink = new \DSI\Entity\OrganisationLink();
        $organisationLink->setOrganisation($this->organisation_2);
        $organisationLink->setLink($this->link_3);
        $this->organisationLinkRep->add($organisationLink);

        $this->assertEquals(
            [$this->link_1, $this->link_2],
            $this->organisationLinkRep->getLinksByOrganisationID($this->organisation_1->getId())
        );
        $this->assertEquals(
            [$this->link_3],
            $this->organisationLinkRep->getLinksByOrganisationID($this->organisation_2->getId())
        );
    }

    /** @test remove */
    public function canRemoveLinkFromOrganisation()
    {
        $organisationLink = new \DSI\Entity\OrganisationLink();
        $organisationLink->setOrganisation($this->organisation_1);
        $organisationLink->setLink($this->link_1);
        $this->organisationLinkRep->add($organisationLink);

        $organisationLink = new \DSI\Entity\OrganisationLink();
        $organisationLink->setOrganisation($this->organisation_1);
        $organisationLink->setLink($this->link_2);
        $this->organisationLinkRep->add($organisationLink);

        $this->assertEquals(
            [$this->link_1, $this->link_2],
            $this->organisationLinkRep->getLinksByOrganisationID($this->organisation_1->getId())
        );

        $organisationLink = new \DSI\Entity\OrganisationLink();
        $organisationLink->setOrganisation($this->organisation_1);
        $organisationLink->setLink($this->link_2);
        $this->organisationLinkRep->remove($organisationLink);

        $this->assertEquals(
            [$this->link_1],
            $this->organisationLinkRep->getLinksByOrganisationID($this->organisation_1->getId())
        );
    }

    /** @test remove */
    public function cannotRemoveNonexistentLinkFromOrganisation()
    {
        $organisationLink = new \DSI\Entity\OrganisationLink();
        $organisationLink->setOrganisation($this->organisation_1);
        $organisationLink->setLink($this->link_2);
        $this->setExpectedException(\DSI\NotFound::class);
        $this->organisationLinkRep->remove($organisationLink);
    }

    private function createOrganisation(int $organisationID)
    {
        $organisation = new \DSI\Entity\Organisation();
        $organisation->setId($organisationID);
        $organisation->setOwner($this->user);
        $this->organisationRepo->insert($organisation);
        return $organisation;
    }
}