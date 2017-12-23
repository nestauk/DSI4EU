<?php

require_once __DIR__ . '/../../../config.php';

class OrganisationTagsRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Repository\OrganisationTagRepo */
    protected $organisationTagsRepo;

    /** @var \DSI\Repository\OrganisationRepo */
    protected $organisationsRepo;

    /** @var \DSI\Repository\UserRepo */
    protected $usersRepo;

    /** @var \DSI\Entity\Organisation */
    protected $organisation_1, $organisation_2, $organisation_3;

    /** @var \DSI\Repository\TagForOrganisationsRepo */
    protected $tagsRepo;

    /** @var \DSI\Entity\TagForOrganisations */
    protected $tag_1, $tag_2, $tag_£;

    public function setUp()
    {
        $this->organisationTagsRepo = new \DSI\Repository\OrganisationTagRepo();
        $this->organisationsRepo = new \DSI\Repository\OrganisationRepo();
        $this->tagsRepo = new \DSI\Repository\TagForOrganisationsRepo();
        $this->usersRepo = new \DSI\Repository\UserRepo();

        $this->organisation_1 = $this->createOrganisation(1);
        $this->organisation_2 = $this->createOrganisation(2);
        $this->organisation_3 = $this->createOrganisation(3);
        $this->tag_1 = $this->createTag(1);
        $this->tag_2 = $this->createTag(2);
        $this->tag_£ = $this->createTag(3);
    }

    public function tearDown()
    {
        $this->organisationTagsRepo->clearAll();
        $this->organisationsRepo->clearAll();
        $this->tagsRepo->clearAll();
        $this->usersRepo->clearAll();
    }

    /** @test saveAsNew */
    public function organisationTagCanBeSaved()
    {
        $organisationTag = new \DSI\Entity\OrganisationTag();
        $organisationTag->setOrganisation($this->organisation_1);
        $organisationTag->setTag($this->tag_1);
        $this->organisationTagsRepo->add($organisationTag);

        $organisationTag = new \DSI\Entity\OrganisationTag();
        $organisationTag->setOrganisation($this->organisation_1);
        $organisationTag->setTag($this->tag_2);
        $this->organisationTagsRepo->add($organisationTag);

        $organisationTag = new \DSI\Entity\OrganisationTag();
        $organisationTag->setOrganisation($this->organisation_2);
        $organisationTag->setTag($this->tag_1);
        $this->organisationTagsRepo->add($organisationTag);

        $organisationTag = new \DSI\Entity\OrganisationTag();
        $organisationTag->setOrganisation($this->organisation_3);
        $organisationTag->setTag($this->tag_1);
        $this->organisationTagsRepo->add($organisationTag);

        $this->assertCount(2, $this->organisationTagsRepo->getByOrganisationID(1));
    }

    /** @test saveAsNew */
    public function cannotAddSameOrganisationTagTwice()
    {
        $organisationTag = new \DSI\Entity\OrganisationTag();
        $organisationTag->setOrganisation($this->organisation_1);
        $organisationTag->setTag($this->tag_1);
        $this->organisationTagsRepo->add($organisationTag);

        $organisationTag = new \DSI\Entity\OrganisationTag();
        $organisationTag->setOrganisation($this->organisation_1);
        $organisationTag->setTag($this->tag_1);
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->organisationTagsRepo->add($organisationTag);
    }

    /** @test saveAsNew */
    public function getAllTagIDsForOrganisation()
    {
        $organisationTag = new \DSI\Entity\OrganisationTag();
        $organisationTag->setOrganisation($this->organisation_1);
        $organisationTag->setTag($this->tag_1);
        $this->organisationTagsRepo->add($organisationTag);

        $organisationTag = new \DSI\Entity\OrganisationTag();
        $organisationTag->setOrganisation($this->organisation_1);
        $organisationTag->setTag($this->tag_2);
        $this->organisationTagsRepo->add($organisationTag);

        $this->assertEquals([1, 2], $this->organisationTagsRepo->getTagIDsForOrganisation($this->organisation_1));
    }

    /** @test saveAsNew */
    public function getAllOrganisationsForTag()
    {
        $organisationTag = new \DSI\Entity\OrganisationTag();
        $organisationTag->setOrganisation($this->organisation_1);
        $organisationTag->setTag($this->tag_1);
        $this->organisationTagsRepo->add($organisationTag);

        $organisationTag = new \DSI\Entity\OrganisationTag();
        $organisationTag->setOrganisation($this->organisation_2);
        $organisationTag->setTag($this->tag_1);
        $this->organisationTagsRepo->add($organisationTag);

        $this->assertCount(2, $this->organisationTagsRepo->getByTagID(1));
    }

    /** @test saveAsNew */
    public function getAllOrganisationIDsForTag()
    {
        $organisationTag = new \DSI\Entity\OrganisationTag();
        $organisationTag->setOrganisation($this->organisation_1);
        $organisationTag->setTag($this->tag_1);
        $this->organisationTagsRepo->add($organisationTag);

        $organisationTag = new \DSI\Entity\OrganisationTag();
        $organisationTag->setOrganisation($this->organisation_2);
        $organisationTag->setTag($this->tag_1);
        $this->organisationTagsRepo->add($organisationTag);

        $this->assertEquals([1, 2], $this->organisationTagsRepo->getOrganisationIDsForTag(1));
    }

    /** @test saveAsNew */
    public function canCheckIfOrganisationHasTagName()
    {
        $organisationTag = new \DSI\Entity\OrganisationTag();
        $organisationTag->setOrganisation($this->organisation_1);
        $organisationTag->setTag($this->tag_1);
        $this->organisationTagsRepo->add($organisationTag);

        $organisationTag = new \DSI\Entity\OrganisationTag();
        $organisationTag->setOrganisation($this->organisation_2);
        $organisationTag->setTag($this->tag_2);
        $this->organisationTagsRepo->add($organisationTag);

        $this->assertTrue($this->organisationTagsRepo->organisationHasTagName(
            $this->organisation_1->getId(), $this->tag_1->getName())
        );
        $this->assertFalse($this->organisationTagsRepo->organisationHasTagName(
            $this->organisation_1->getId(), $this->tag_2->getName())
        );
        $this->assertTrue($this->organisationTagsRepo->organisationHasTagName(
            $this->organisation_2->getId(), $this->tag_2->getName())
        );
        $this->assertFalse($this->organisationTagsRepo->organisationHasTagName(
            $this->organisation_2->getId(), $this->tag_1->getName())
        );
    }

    /** @test saveAsNew */
    public function canGetTagNamesByOrganisationID()
    {
        $organisationTag = new \DSI\Entity\OrganisationTag();
        $organisationTag->setOrganisation($this->organisation_1);
        $organisationTag->setTag($this->tag_1);
        $this->organisationTagsRepo->add($organisationTag);

        $organisationTag = new \DSI\Entity\OrganisationTag();
        $organisationTag->setOrganisation($this->organisation_1);
        $organisationTag->setTag($this->tag_2);
        $this->organisationTagsRepo->add($organisationTag);

        $organisationTag = new \DSI\Entity\OrganisationTag();
        $organisationTag->setOrganisation($this->organisation_2);
        $organisationTag->setTag($this->tag_£);
        $this->organisationTagsRepo->add($organisationTag);

        $this->assertEquals(
            [$this->tag_1->getName(), $this->tag_2->getName()],
            $this->organisationTagsRepo->getTagNamesByOrganisation($this->organisation_1)
        );
        $this->assertEquals(
            [$this->tag_£->getName()],
            $this->organisationTagsRepo->getTagNamesByOrganisation($this->organisation_2)
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
        $tag = new \DSI\Entity\TagForOrganisations();
        $tag->setId($tagID);
        $tag->setName('tag-' . $tagID);
        $this->tagsRepo->saveAsNew($tag);
        return $tag;
    }
}