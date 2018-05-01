<?php

require_once __DIR__ . '/../../../config.php';

class OrganisationMemberInvitationRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Repository\OrganisationMemberInvitationRepo */
    protected $organisationMemberInvitationRepository;

    /** @var \DSI\Repository\OrganisationRepo */
    protected $organisationsRepo;

    /** @var \DSI\Repository\UserRepo */
    protected $usersRepo;

    /** @var \DSI\Entity\Organisation */
    protected $organisation_1, $organisation_2, $organisation_3;

    /** @var \DSI\Entity\User */
    protected $user_1, $user_2, $user_3;

    public function setUp()
    {
        $this->organisationMemberInvitationRepository = new \DSI\Repository\OrganisationMemberInvitationRepo();
        $this->organisationsRepo = new \DSI\Repository\OrganisationRepo();
        $this->usersRepo = new \DSI\Repository\UserRepo();

        $this->user_1 = $this->createUser();
        $this->user_2 = $this->createUser();
        $this->user_3 = $this->createUser();

        $this->organisation_1 = $this->createOrganisation($this->user_1);
        $this->organisation_2 = $this->createOrganisation($this->user_2);
        $this->organisation_3 = $this->createOrganisation($this->user_3);
    }

    public function tearDown()
    {
        $this->organisationMemberInvitationRepository->clearAll();
        $this->organisationsRepo->clearAll();
        $this->usersRepo->clearAll();
    }

    /** @test saveAsNew */
    public function organisationMemberInvitationCanBeSaved()
    {
        $organisationMemberInvitation = new \DSI\Entity\OrganisationMemberInvitation();
        $organisationMemberInvitation->setOrganisation($this->organisation_1);
        $organisationMemberInvitation->setMember($this->user_1);
        $this->organisationMemberInvitationRepository->add($organisationMemberInvitation);

        $organisationMemberInvitation = new \DSI\Entity\OrganisationMemberInvitation();
        $organisationMemberInvitation->setOrganisation($this->organisation_1);
        $organisationMemberInvitation->setMember($this->user_2);
        $this->organisationMemberInvitationRepository->add($organisationMemberInvitation);

        $organisationMemberInvitation = new \DSI\Entity\OrganisationMemberInvitation();
        $organisationMemberInvitation->setOrganisation($this->organisation_2);
        $organisationMemberInvitation->setMember($this->user_1);
        $this->organisationMemberInvitationRepository->add($organisationMemberInvitation);

        $organisationMemberInvitation = new \DSI\Entity\OrganisationMemberInvitation();
        $organisationMemberInvitation->setOrganisation($this->organisation_3);
        $organisationMemberInvitation->setMember($this->user_1);
        $this->organisationMemberInvitationRepository->add($organisationMemberInvitation);

        $this->assertCount(2, $this->organisationMemberInvitationRepository->getByOrganisationID(
            $this->organisation_1->getId()
        ));
    }

    /** @test saveAsNew */
    public function cannotAddSameOrganisationMemberInvitationTwice()
    {
        $organisationMemberInvitation = new \DSI\Entity\OrganisationMemberInvitation();
        $organisationMemberInvitation->setOrganisation($this->organisation_1);
        $organisationMemberInvitation->setMember($this->user_1);
        $this->organisationMemberInvitationRepository->add($organisationMemberInvitation);

        $organisationMemberInvitation = new \DSI\Entity\OrganisationMemberInvitation();
        $organisationMemberInvitation->setOrganisation($this->organisation_1);
        $organisationMemberInvitation->setMember($this->user_1);
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->organisationMemberInvitationRepository->add($organisationMemberInvitation);
    }

    /** @test saveAsNew */
    public function getAllOrganisationsForMember()
    {
        $organisationMemberInvitation = new \DSI\Entity\OrganisationMemberInvitation();
        $organisationMemberInvitation->setOrganisation($this->organisation_1);
        $organisationMemberInvitation->setMember($this->user_1);
        $this->organisationMemberInvitationRepository->add($organisationMemberInvitation);

        $organisationMemberInvitation = new \DSI\Entity\OrganisationMemberInvitation();
        $organisationMemberInvitation->setOrganisation($this->organisation_2);
        $organisationMemberInvitation->setMember($this->user_1);
        $this->organisationMemberInvitationRepository->add($organisationMemberInvitation);

        $this->assertCount(2, $this->organisationMemberInvitationRepository->getByMemberID(
            $this->user_1->getId()
        ));
    }

    /** @test saveAsNew */
    public function getAllOrganisationIDsForMember()
    {
        $organisationMemberInvitation = new \DSI\Entity\OrganisationMemberInvitation();
        $organisationMemberInvitation->setOrganisation($this->organisation_1);
        $organisationMemberInvitation->setMember($this->user_1);
        $this->organisationMemberInvitationRepository->add($organisationMemberInvitation);

        $this->assertEquals([1], $this->organisationMemberInvitationRepository->getOrganisationIDsForMember(
            $this->user_1->getId()
        ));

        $organisationMemberInvitation = new \DSI\Entity\OrganisationMemberInvitation();
        $organisationMemberInvitation->setOrganisation($this->organisation_2);
        $organisationMemberInvitation->setMember($this->user_1);
        $this->organisationMemberInvitationRepository->add($organisationMemberInvitation);

        $this->assertEquals([1, 2], $this->organisationMemberInvitationRepository->getOrganisationIDsForMember(
            $this->user_1->getId()
        ));

        $organisationMemberInvitation = new \DSI\Entity\OrganisationMemberInvitation();
        $organisationMemberInvitation->setOrganisation($this->organisation_1);
        $organisationMemberInvitation->setMember($this->user_1);
        $this->organisationMemberInvitationRepository->remove($organisationMemberInvitation);

        $this->assertEquals([2], $this->organisationMemberInvitationRepository->getOrganisationIDsForMember(
            $this->user_1->getId()
        ));
    }

    /** @test saveAsNew */
    public function getMemberIDsForOrganisation()
    {
        $organisationMemberInvitation = new \DSI\Entity\OrganisationMemberInvitation();
        $organisationMemberInvitation->setOrganisation($this->organisation_1);
        $organisationMemberInvitation->setMember($this->user_1);
        $this->organisationMemberInvitationRepository->add($organisationMemberInvitation);

        $this->assertEquals([1], $this->organisationMemberInvitationRepository->getMemberIDsForOrganisation(
            $this->organisation_1->getId()
        ));

        $organisationMemberInvitation = new \DSI\Entity\OrganisationMemberInvitation();
        $organisationMemberInvitation->setOrganisation($this->organisation_1);
        $organisationMemberInvitation->setMember($this->user_2);
        $this->organisationMemberInvitationRepository->add($organisationMemberInvitation);

        $this->assertEquals([1, 2], $this->organisationMemberInvitationRepository->getMemberIDsForOrganisation(
            $this->organisation_1->getId()
        ));

        $organisationMemberInvitation = new \DSI\Entity\OrganisationMemberInvitation();
        $organisationMemberInvitation->setOrganisation($this->organisation_1);
        $organisationMemberInvitation->setMember($this->user_1);
        $this->organisationMemberInvitationRepository->remove($organisationMemberInvitation);

        $this->assertEquals([2], $this->organisationMemberInvitationRepository->getMemberIDsForOrganisation(
            $this->organisation_1->getId()
        ));
    }

    /** @test saveAsNew */
    public function getMembersForOrganisation()
    {
        $organisationMemberInvitation = new \DSI\Entity\OrganisationMemberInvitation();
        $organisationMemberInvitation->setOrganisation($this->organisation_1);
        $organisationMemberInvitation->setMember($this->user_1);
        $this->organisationMemberInvitationRepository->add($organisationMemberInvitation);

        $this->assertCount(1, $this->organisationMemberInvitationRepository->getMembersForOrganisation(
            $this->organisation_1->getId()
        ));

        $organisationMemberInvitation = new \DSI\Entity\OrganisationMemberInvitation();
        $organisationMemberInvitation->setOrganisation($this->organisation_1);
        $organisationMemberInvitation->setMember($this->user_2);
        $this->organisationMemberInvitationRepository->add($organisationMemberInvitation);

        $this->assertCount(2, $this->organisationMemberInvitationRepository->getMembersForOrganisation(
            $this->organisation_1->getId()
        ));

        $organisationMemberInvitation = new \DSI\Entity\OrganisationMemberInvitation();
        $organisationMemberInvitation->setOrganisation($this->organisation_1);
        $organisationMemberInvitation->setMember($this->user_1);
        $this->organisationMemberInvitationRepository->remove($organisationMemberInvitation);

        $this->assertCount(1, $this->organisationMemberInvitationRepository->getMembersForOrganisation(
            $this->organisation_1->getId()
        ));
    }

    /** @test saveAsNew */
    public function cannotRemoveNonexistentInvitation()
    {
        $organisationMemberInvitation = new \DSI\Entity\OrganisationMemberInvitation();
        $organisationMemberInvitation->setOrganisation($this->organisation_1);
        $organisationMemberInvitation->setMember($this->user_1);
        $this->setExpectedException(\DSI\NotFound::class);
        $this->organisationMemberInvitationRepository->remove($organisationMemberInvitation);
    }

    /** @test saveAsNew */
    public function canCheckIfMemberHasInvitationToOrganisation()
    {
        $organisationMemberInvitation = new \DSI\Entity\OrganisationMemberInvitation();
        $organisationMemberInvitation->setOrganisation($this->organisation_1);
        $organisationMemberInvitation->setMember($this->user_1);
        $this->organisationMemberInvitationRepository->add($organisationMemberInvitation);

        $organisationMemberInvitation = new \DSI\Entity\OrganisationMemberInvitation();
        $organisationMemberInvitation->setOrganisation($this->organisation_2);
        $organisationMemberInvitation->setMember($this->user_2);
        $this->organisationMemberInvitationRepository->add($organisationMemberInvitation);

        $this->assertTrue($this->organisationMemberInvitationRepository->userIdHasInvitationToOrganisationId(
            $this->user_1->getId(), $this->organisation_1->getId())
        );
        $this->assertFalse($this->organisationMemberInvitationRepository->userIdHasInvitationToOrganisationId(
            $this->user_2->getId(), $this->organisation_1->getId())
        );
        $this->assertTrue($this->organisationMemberInvitationRepository->userIdHasInvitationToOrganisationId(
            $this->user_2->getId(), $this->organisation_2->getId())
        );
        $this->assertFalse($this->organisationMemberInvitationRepository->userIdHasInvitationToOrganisationId(
            $this->user_1->getId(), $this->organisation_2->getId())
        );
    }

    private function createOrganisation(\DSI\Entity\User $user)
    {
        $organisation = new \DSI\Entity\Organisation();
        $organisation->setOwner($user);
        $this->organisationsRepo->insert($organisation);
        return $organisation;
    }

    private function createUser()
    {
        $user = new \DSI\Entity\User();
        $this->usersRepo->insert($user);
        return $user;
    }
}