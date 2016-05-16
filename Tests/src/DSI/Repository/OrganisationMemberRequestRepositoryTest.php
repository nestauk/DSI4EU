<?php

require_once __DIR__ . '/../../../config.php';

class OrganisationMemberRequestRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Repository\OrganisationMemberRequestRepository */
    protected $organisationMemberRequestRepository;

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
        $this->organisationMemberRequestRepository = new \DSI\Repository\OrganisationMemberRequestRepository();
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
        $this->organisationMemberRequestRepository->clearAll();
        $this->organisationsRepo->clearAll();
        $this->usersRepo->clearAll();
    }

    /** @test saveAsNew */
    public function organisationMemberRequestCanBeSaved()
    {
        $organisationMemberRequest = new \DSI\Entity\OrganisationMemberRequest();
        $organisationMemberRequest->setOrganisation($this->organisation_1);
        $organisationMemberRequest->setMember($this->user_1);
        $this->organisationMemberRequestRepository->add($organisationMemberRequest);

        $organisationMemberRequest = new \DSI\Entity\OrganisationMemberRequest();
        $organisationMemberRequest->setOrganisation($this->organisation_1);
        $organisationMemberRequest->setMember($this->user_2);
        $this->organisationMemberRequestRepository->add($organisationMemberRequest);

        $organisationMemberRequest = new \DSI\Entity\OrganisationMemberRequest();
        $organisationMemberRequest->setOrganisation($this->organisation_2);
        $organisationMemberRequest->setMember($this->user_1);
        $this->organisationMemberRequestRepository->add($organisationMemberRequest);

        $organisationMemberRequest = new \DSI\Entity\OrganisationMemberRequest();
        $organisationMemberRequest->setOrganisation($this->organisation_3);
        $organisationMemberRequest->setMember($this->user_1);
        $this->organisationMemberRequestRepository->add($organisationMemberRequest);

        $this->assertCount(2, $this->organisationMemberRequestRepository->getByOrganisationID(
            $this->organisation_1->getId()
        ));
    }

    /** @test saveAsNew */
    public function cannotAddSameOrganisationMemberRequestTwice()
    {
        $organisationMemberRequest = new \DSI\Entity\OrganisationMemberRequest();
        $organisationMemberRequest->setOrganisation($this->organisation_1);
        $organisationMemberRequest->setMember($this->user_1);
        $this->organisationMemberRequestRepository->add($organisationMemberRequest);

        $organisationMemberRequest = new \DSI\Entity\OrganisationMemberRequest();
        $organisationMemberRequest->setOrganisation($this->organisation_1);
        $organisationMemberRequest->setMember($this->user_1);
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->organisationMemberRequestRepository->add($organisationMemberRequest);
    }

    /** @test saveAsNew */
    public function getAllRequestMemberIDsForOrganisation()
    {
        $organisationMemberRequest = new \DSI\Entity\OrganisationMemberRequest();
        $organisationMemberRequest->setOrganisation($this->organisation_1);
        $organisationMemberRequest->setMember($this->user_1);
        $this->organisationMemberRequestRepository->add($organisationMemberRequest);

        $organisationMemberRequest = new \DSI\Entity\OrganisationMemberRequest();
        $organisationMemberRequest->setOrganisation($this->organisation_1);
        $organisationMemberRequest->setMember($this->user_2);
        $this->organisationMemberRequestRepository->add($organisationMemberRequest);

        $this->assertEquals(
            [$this->user_1->getId(), $this->user_2->getId()],
            $this->organisationMemberRequestRepository->getMemberIDsForOrganisation(
                $this->organisation_1->getId()
            ));
    }

    /** @test saveAsNew */
    public function getAllOrganisationsForMember()
    {
        $organisationMemberRequest = new \DSI\Entity\OrganisationMemberRequest();
        $organisationMemberRequest->setOrganisation($this->organisation_1);
        $organisationMemberRequest->setMember($this->user_1);
        $this->organisationMemberRequestRepository->add($organisationMemberRequest);

        $organisationMemberRequest = new \DSI\Entity\OrganisationMemberRequest();
        $organisationMemberRequest->setOrganisation($this->organisation_2);
        $organisationMemberRequest->setMember($this->user_1);
        $this->organisationMemberRequestRepository->add($organisationMemberRequest);

        $this->assertCount(2, $this->organisationMemberRequestRepository->getByMemberID(
            $this->user_1->getId()
        ));
    }

    /** @test saveAsNew */
    public function getAllOrganisationIDsForMember()
    {
        $organisationMemberRequest = new \DSI\Entity\OrganisationMemberRequest();
        $organisationMemberRequest->setOrganisation($this->organisation_1);
        $organisationMemberRequest->setMember($this->user_1);
        $this->organisationMemberRequestRepository->add($organisationMemberRequest);

        $organisationMemberRequest = new \DSI\Entity\OrganisationMemberRequest();
        $organisationMemberRequest->setOrganisation($this->organisation_2);
        $organisationMemberRequest->setMember($this->user_1);
        $this->organisationMemberRequestRepository->add($organisationMemberRequest);

        $this->assertEquals([1, 2], $this->organisationMemberRequestRepository->getOrganisationIDsForMember(
            $this->user_1->getId()
        ));
    }

    /** @test saveAsNew */
    public function canCheckIfOrganisationHasMemberRequest()
    {
        $organisationMemberRequest = new \DSI\Entity\OrganisationMemberRequest();
        $organisationMemberRequest->setOrganisation($this->organisation_1);
        $organisationMemberRequest->setMember($this->user_1);
        $this->organisationMemberRequestRepository->add($organisationMemberRequest);

        $organisationMemberRequest = new \DSI\Entity\OrganisationMemberRequest();
        $organisationMemberRequest->setOrganisation($this->organisation_2);
        $organisationMemberRequest->setMember($this->user_2);
        $this->organisationMemberRequestRepository->add($organisationMemberRequest);

        $this->assertTrue($this->organisationMemberRequestRepository->organisationHasRequestFromMember(
            $this->organisation_1->getId(), $this->user_1->getId())
        );
        $this->assertFalse($this->organisationMemberRequestRepository->organisationHasRequestFromMember(
            $this->organisation_1->getId(), $this->user_2->getId())
        );
        $this->assertTrue($this->organisationMemberRequestRepository->organisationHasRequestFromMember(
            $this->organisation_2->getId(), $this->user_2->getId())
        );
        $this->assertFalse($this->organisationMemberRequestRepository->organisationHasRequestFromMember(
            $this->organisation_2->getId(), $this->user_1->getId())
        );
    }

    private function createOrganisation(\DSI\Entity\User $user)
    {
        $organisation = new \DSI\Entity\Organisation();
        $organisation->setOwner($user);
        $this->organisationsRepo->saveAsNew($organisation);
        return $organisation;
    }

    private function createUser()
    {
        $user = new \DSI\Entity\User();
        $this->usersRepo->insert($user);
        return $user;
    }
}