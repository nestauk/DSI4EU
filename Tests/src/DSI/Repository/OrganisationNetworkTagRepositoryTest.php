<?php

require_once __DIR__ . '/../../../config.php';

class OrganisationNetworkTagRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Repository\OrganisationNetworkTagRepository */
    protected $organisationNetworkTagRepo;

    /** @var \DSI\Repository\OrganisationRepository */
    protected $organisationsRepo;

    /** @var \DSI\Repository\UserRepository */
    protected $usersRepo;

    /** @var \DSI\Entity\Organisation */
    protected $organisation_1, $organisation_2, $organisation_3;

    /** @var \DSI\Repository\NetworkTagRepository */
    protected $networkTagRepo;

    /** @var \DSI\Entity\NetworkTag */
    protected $tag_1, $tag_2, $tag_3;

    public function setUp()
    {
        $this->organisationNetworkTagRepo = new \DSI\Repository\OrganisationNetworkTagRepository();
        $this->organisationsRepo = new \DSI\Repository\OrganisationRepository();
        $this->networkTagRepo = new \DSI\Repository\NetworkTagRepository();
        $this->usersRepo = new \DSI\Repository\UserRepository();

        $this->organisation_1 = $this->createOrganisation(1);
        $this->organisation_2 = $this->createOrganisation(2);
        $this->organisation_3 = $this->createOrganisation(3);
        $this->tag_1 = $this->createTag(1);
        $this->tag_2 = $this->createTag(2);
        $this->tag_3 = $this->createTag(3);
    }

    public function tearDown()
    {
        $this->organisationNetworkTagRepo->clearAll();
        $this->organisationsRepo->clearAll();
        $this->networkTagRepo->clearAll();
        $this->usersRepo->clearAll();
    }

    /** @test saveAsNew */
    public function organisationTagCanBeSaved()
    {
        $organisationTag = new \DSI\Entity\OrganisationNetworkTag();
        $organisationTag->setOrganisation($this->organisation_1);
        $organisationTag->setTag($this->tag_1);
        $this->organisationNetworkTagRepo->add($organisationTag);

        $organisationTag = new \DSI\Entity\OrganisationNetworkTag();
        $organisationTag->setOrganisation($this->organisation_1);
        $organisationTag->setTag($this->tag_2);
        $this->organisationNetworkTagRepo->add($organisationTag);

        $organisationTag = new \DSI\Entity\OrganisationNetworkTag();
        $organisationTag->setOrganisation($this->organisation_2);
        $organisationTag->setTag($this->tag_1);
        $this->organisationNetworkTagRepo->add($organisationTag);

        $organisationTag = new \DSI\Entity\OrganisationNetworkTag();
        $organisationTag->setOrganisation($this->organisation_3);
        $organisationTag->setTag($this->tag_1);
        $this->organisationNetworkTagRepo->add($organisationTag);

        $this->assertCount(2, $this->organisationNetworkTagRepo->getByOrganisationID(1));
    }

    /** @test saveAsNew */
    public function cannotAddSameOrganisationTagTwice()
    {
        $organisationTag = new \DSI\Entity\OrganisationNetworkTag();
        $organisationTag->setOrganisation($this->organisation_1);
        $organisationTag->setTag($this->tag_1);
        $this->organisationNetworkTagRepo->add($organisationTag);

        $organisationTag = new \DSI\Entity\OrganisationNetworkTag();
        $organisationTag->setOrganisation($this->organisation_1);
        $organisationTag->setTag($this->tag_1);
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->organisationNetworkTagRepo->add($organisationTag);
    }

    /** @test saveAsNew */
    public function getAllTagIDsForOrganisation()
    {
        $organisationTag = new \DSI\Entity\OrganisationNetworkTag();
        $organisationTag->setOrganisation($this->organisation_1);
        $organisationTag->setTag($this->tag_1);
        $this->organisationNetworkTagRepo->add($organisationTag);

        $organisationTag = new \DSI\Entity\OrganisationNetworkTag();
        $organisationTag->setOrganisation($this->organisation_1);
        $organisationTag->setTag($this->tag_2);
        $this->organisationNetworkTagRepo->add($organisationTag);

        $this->assertEquals([1, 2], $this->organisationNetworkTagRepo->getTagIDsForOrganisation($this->organisation_1));
    }

    /** @test saveAsNew */
    public function getAllOrganisationsForTag()
    {
        $organisationTag = new \DSI\Entity\OrganisationNetworkTag();
        $organisationTag->setOrganisation($this->organisation_1);
        $organisationTag->setTag($this->tag_1);
        $this->organisationNetworkTagRepo->add($organisationTag);

        $organisationTag = new \DSI\Entity\OrganisationNetworkTag();
        $organisationTag->setOrganisation($this->organisation_2);
        $organisationTag->setTag($this->tag_1);
        $this->organisationNetworkTagRepo->add($organisationTag);

        $this->assertCount(2, $this->organisationNetworkTagRepo->getByTagID(1));
    }

    /** @test saveAsNew */
    public function getAllOrganisationIDsForTag()
    {
        $organisationTag = new \DSI\Entity\OrganisationNetworkTag();
        $organisationTag->setOrganisation($this->organisation_1);
        $organisationTag->setTag($this->tag_1);
        $this->organisationNetworkTagRepo->add($organisationTag);

        $organisationTag = new \DSI\Entity\OrganisationNetworkTag();
        $organisationTag->setOrganisation($this->organisation_2);
        $organisationTag->setTag($this->tag_1);
        $this->organisationNetworkTagRepo->add($organisationTag);

        $this->assertEquals([1, 2], $this->organisationNetworkTagRepo->getOrganisationIDsForTag(1));
    }

    /** @test saveAsNew */
    public function canCheckIfOrganisationHasTagName()
    {
        $organisationTag = new \DSI\Entity\OrganisationNetworkTag();
        $organisationTag->setOrganisation($this->organisation_1);
        $organisationTag->setTag($this->tag_1);
        $this->organisationNetworkTagRepo->add($organisationTag);

        $organisationTag = new \DSI\Entity\OrganisationNetworkTag();
        $organisationTag->setOrganisation($this->organisation_2);
        $organisationTag->setTag($this->tag_2);
        $this->organisationNetworkTagRepo->add($organisationTag);

        $this->assertTrue($this->organisationNetworkTagRepo->organisationHasTagName(
            $this->organisation_1->getId(), $this->tag_1->getName())
        );
        $this->assertFalse($this->organisationNetworkTagRepo->organisationHasTagName(
            $this->organisation_1->getId(), $this->tag_2->getName())
        );
        $this->assertTrue($this->organisationNetworkTagRepo->organisationHasTagName(
            $this->organisation_2->getId(), $this->tag_2->getName())
        );
        $this->assertFalse($this->organisationNetworkTagRepo->organisationHasTagName(
            $this->organisation_2->getId(), $this->tag_1->getName())
        );
    }

    /** @test saveAsNew */
    public function canGetTagNamesByOrganisationID()
    {
        $organisationTag = new \DSI\Entity\OrganisationNetworkTag();
        $organisationTag->setOrganisation($this->organisation_1);
        $organisationTag->setTag($this->tag_1);
        $this->organisationNetworkTagRepo->add($organisationTag);

        $organisationTag = new \DSI\Entity\OrganisationNetworkTag();
        $organisationTag->setOrganisation($this->organisation_1);
        $organisationTag->setTag($this->tag_2);
        $this->organisationNetworkTagRepo->add($organisationTag);

        $organisationTag = new \DSI\Entity\OrganisationNetworkTag();
        $organisationTag->setOrganisation($this->organisation_2);
        $organisationTag->setTag($this->tag_3);
        $this->organisationNetworkTagRepo->add($organisationTag);

        $this->assertEquals(
            [$this->tag_1->getName(), $this->tag_2->getName()],
            $this->organisationNetworkTagRepo->getTagNamesByOrganisation($this->organisation_1)
        );
        $this->assertEquals(
            [$this->tag_3->getName()],
            $this->organisationNetworkTagRepo->getTagNamesByOrganisation($this->organisation_2)
        );
    }


    private function createOrganisation(int $organisationID)
    {
        $user = new \DSI\Entity\User();
        $this->usersRepo->insert($user);

        $organisation = new \DSI\Entity\Organisation();
        $organisation->setId($organisationID);
        $organisation->setOwner($user);
        $this->organisationsRepo->insert($organisation);
        return $organisation;
    }

    private function createTag(int $tagID)
    {
        $tag = new \DSI\Entity\NetworkTag();
        $tag->setId($tagID);
        $tag->setName('tag-' . $tagID);
        $this->networkTagRepo->saveAsNew($tag);
        return $tag;
    }
}