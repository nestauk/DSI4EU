<?php

require_once __DIR__ . '/../../../config.php';

class OrganisationMemberRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Repository\OrganisationMemberRepository */
    protected $organisationMemberRepo;

    /** @var \DSI\Repository\OrganisationRepository */
    protected $organisationsRepo;

    /** @var \DSI\Repository\UserRepository */
    protected $usersRepo;

    /** @var \DSI\Entity\Organisation */
    protected $organisation_1, $organisation_2, $organisation_3;

    /** @var \DSI\Entity\User */
    protected $user_1, $user_2, $user_3;

    public function setUp()
    {
        $this->organisationMemberRepo = new \DSI\Repository\OrganisationMemberRepository();
        $this->organisationsRepo = new \DSI\Repository\OrganisationRepository();
        $this->usersRepo = new \DSI\Repository\UserRepository();

        $this->user_1 = $this->createUser();
        $this->user_2 = $this->createUser();
        $this->user_3 = $this->createUser();

        $this->organisation_1 = $this->createOrganisation($this->user_1);
        $this->organisation_2 = $this->createOrganisation($this->user_2);
        $this->organisation_3 = $this->createOrganisation($this->user_3);
    }

    public function tearDown()
    {
        $this->organisationMemberRepo->clearAll();
        $this->organisationsRepo->clearAll();
        $this->usersRepo->clearAll();
    }

    /** @test saveAsNew */
    public function organisationMemberCanBeSaved()
    {
        $organisationMember = new \DSI\Entity\OrganisationMember();
        $organisationMember->setOrganisation($this->organisation_1);
        $organisationMember->setMember($this->user_1);
        $this->organisationMemberRepo->add($organisationMember);

        $organisationMember = new \DSI\Entity\OrganisationMember();
        $organisationMember->setOrganisation($this->organisation_1);
        $organisationMember->setMember($this->user_2);
        $this->organisationMemberRepo->add($organisationMember);

        $organisationMember = new \DSI\Entity\OrganisationMember();
        $organisationMember->setOrganisation($this->organisation_2);
        $organisationMember->setMember($this->user_1);
        $this->organisationMemberRepo->add($organisationMember);

        $organisationMember = new \DSI\Entity\OrganisationMember();
        $organisationMember->setOrganisation($this->organisation_3);
        $organisationMember->setMember($this->user_1);
        $this->organisationMemberRepo->add($organisationMember);

        $this->assertCount(2, $this->organisationMemberRepo->getByOrganisationID(
            $this->organisation_1->getId()
        ));
    }

    /** @test saveAsNew */
    public function cannotAddSameOrganisationMemberTwice()
    {
        $organisationMember = new \DSI\Entity\OrganisationMember();
        $organisationMember->setOrganisation($this->organisation_1);
        $organisationMember->setMember($this->user_1);
        $this->organisationMemberRepo->add($organisationMember);

        $organisationMember = new \DSI\Entity\OrganisationMember();
        $organisationMember->setOrganisation($this->organisation_1);
        $organisationMember->setMember($this->user_1);
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->organisationMemberRepo->add($organisationMember);
    }

    /** @test saveAsNew */
    public function getAllMemberIDsForOrganisation()
    {
        $organisationMember = new \DSI\Entity\OrganisationMember();
        $organisationMember->setOrganisation($this->organisation_1);
        $organisationMember->setMember($this->user_1);
        $this->organisationMemberRepo->add($organisationMember);

        $organisationMember = new \DSI\Entity\OrganisationMember();
        $organisationMember->setOrganisation($this->organisation_1);
        $organisationMember->setMember($this->user_2);
        $this->organisationMemberRepo->add($organisationMember);

        $this->assertEquals(
            [$this->user_1->getId(), $this->user_2->getId()],
            $this->organisationMemberRepo->getMemberIDsForOrganisation(
                $this->organisation_1->getId()
            ));
    }

    /** @test saveAsNew */
    public function getAllOrganisationsForMember()
    {
        $organisationMember = new \DSI\Entity\OrganisationMember();
        $organisationMember->setOrganisation($this->organisation_1);
        $organisationMember->setMember($this->user_1);
        $this->organisationMemberRepo->add($organisationMember);

        $organisationMember = new \DSI\Entity\OrganisationMember();
        $organisationMember->setOrganisation($this->organisation_2);
        $organisationMember->setMember($this->user_1);
        $this->organisationMemberRepo->add($organisationMember);

        $this->assertCount(2, $this->organisationMemberRepo->getByMemberID(
            $this->user_1->getId()
        ));
    }

    /** @test saveAsNew */
    public function getAllOrganisationIDsForMember()
    {
        $organisationMember = new \DSI\Entity\OrganisationMember();
        $organisationMember->setOrganisation($this->organisation_1);
        $organisationMember->setMember($this->user_1);
        $this->organisationMemberRepo->add($organisationMember);

        $organisationMember = new \DSI\Entity\OrganisationMember();
        $organisationMember->setOrganisation($this->organisation_2);
        $organisationMember->setMember($this->user_1);
        $this->organisationMemberRepo->add($organisationMember);

        $this->assertEquals([1, 2], $this->organisationMemberRepo->getOrganisationIDsForMember(
            $this->user_1->getId()
        ));
    }

    /** @test saveAsNew */
    public function canCheckIfOrganisationHasMember()
    {
        $organisationMember = new \DSI\Entity\OrganisationMember();
        $organisationMember->setOrganisation($this->organisation_1);
        $organisationMember->setMember($this->user_1);
        $this->organisationMemberRepo->add($organisationMember);

        $organisationMember = new \DSI\Entity\OrganisationMember();
        $organisationMember->setOrganisation($this->organisation_2);
        $organisationMember->setMember($this->user_2);
        $this->organisationMemberRepo->add($organisationMember);

        $this->assertTrue($this->organisationMemberRepo->organisationHasMember(
            $this->organisation_1->getId(), $this->user_1->getId())
        );
        $this->assertFalse($this->organisationMemberRepo->organisationHasMember(
            $this->organisation_1->getId(), $this->user_2->getId())
        );
        $this->assertTrue($this->organisationMemberRepo->organisationHasMember(
            $this->organisation_2->getId(), $this->user_2->getId())
        );
        $this->assertFalse($this->organisationMemberRepo->organisationHasMember(
            $this->organisation_2->getId(), $this->user_1->getId())
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