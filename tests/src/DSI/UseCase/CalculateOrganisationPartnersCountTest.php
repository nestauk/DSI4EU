<?php

require_once __DIR__ . '/../../../config.php';

class CalculateOrganisationPartnersCountTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\CalculateOrganisationPartnersCount */
    private $calculateOrgPartnersCntCmd;

    /** @var \DSI\Entity\User */
    private $user;

    /** @var \DSI\Repository\UserRepo */
    private $userRepo;

    /** @var \DSI\Repository\OrganisationProjectRepo */
    private $organisationProjectRepo;

    /** @var \DSI\Repository\OrganisationRepo */
    private $organisationRepo;

    /** @var \DSI\Repository\ProjectRepo */
    private $projectRepo;

    public function setUp()
    {
        $this->calculateOrgPartnersCntCmd = new \DSI\UseCase\CalculateOrganisationPartnersCount();
        $this->organisationProjectRepo = new \DSI\Repository\OrganisationProjectRepo();
        $this->organisationRepo = new \DSI\Repository\OrganisationRepo();
        $this->projectRepo = new \DSI\Repository\ProjectRepo();
        $this->userRepo = new \DSI\Repository\UserRepo();

        $this->user = new \DSI\Entity\User();
        $this->userRepo->insert($this->user);
    }

    public function tearDown()
    {
        $this->organisationProjectRepo->clearAll();
        $this->organisationRepo->clearAll();
        $this->projectRepo->clearAll();
    }

    /** @test */
    public function canCalculateCorrectlyForNoOrganisation()
    {
        $organisation1 = $this->createOrganisation();
        $organisation2 = $this->createOrganisation();

        $project1 = $this->createProject();
        $project2 = $this->createProject();

        $this->addProjectToOrganisation($project1, $organisation1);
        $this->addProjectToOrganisation($project2, $organisation2);

        $this->calculateOrgPartnersCntCmd->setOrganisation($organisation1);
        $this->calculateOrgPartnersCntCmd->exec();
        $organisation = $this->organisationRepo->getById($organisation1->getId());
        $this->assertEquals(0, $organisation->getPartnersCount());

        $this->calculateOrgPartnersCntCmd->setOrganisation($organisation2);
        $this->calculateOrgPartnersCntCmd->exec();
        $organisation = $this->organisationRepo->getById($organisation2->getId());
        $this->assertEquals(0, $organisation->getPartnersCount());
    }

    /** @test */
    public function canCalculateCorrectlyForOneOrganisation()
    {
        $organisation1 = $this->createOrganisation();
        $organisation2 = $this->createOrganisation();

        $project1 = $this->createProject();

        $this->addProjectToOrganisation($project1, $organisation1);
        $this->addProjectToOrganisation($project1, $organisation2);


        $this->calculateOrgPartnersCntCmd->setOrganisation($organisation1);
        $this->calculateOrgPartnersCntCmd->exec();
        $organisation = $this->organisationRepo->getById($organisation1->getId());
        $this->assertEquals(1, $organisation->getPartnersCount());


        $this->calculateOrgPartnersCntCmd->setOrganisation($organisation2);
        $this->calculateOrgPartnersCntCmd->exec();
        $organisation = $this->organisationRepo->getById($organisation2->getId());
        $this->assertEquals(1, $organisation->getPartnersCount());
    }

    /** @test */
    public function canCalculateCorrectlyForTwoOrganisation()
    {
        $organisation1 = $this->createOrganisation();
        $organisation2 = $this->createOrganisation();
        $organisation3 = $this->createOrganisation();

        $project1 = $this->createProject();

        $this->addProjectToOrganisation($project1, $organisation1);
        $this->addProjectToOrganisation($project1, $organisation2);
        $this->addProjectToOrganisation($project1, $organisation3);


        $this->calculateOrgPartnersCntCmd->setOrganisation($organisation1);
        $this->calculateOrgPartnersCntCmd->exec();
        $organisation = $this->organisationRepo->getById($organisation1->getId());
        $this->assertEquals(2, $organisation->getPartnersCount());


        $this->calculateOrgPartnersCntCmd->setOrganisation($organisation2);
        $this->calculateOrgPartnersCntCmd->exec();
        $organisation = $this->organisationRepo->getById($organisation2->getId());
        $this->assertEquals(2, $organisation->getPartnersCount());


        $this->calculateOrgPartnersCntCmd->setOrganisation($organisation3);
        $this->calculateOrgPartnersCntCmd->exec();
        $organisation = $this->organisationRepo->getById($organisation3->getId());
        $this->assertEquals(2, $organisation->getPartnersCount());
    }

    /**
     * @param $project1
     * @param $organisation1
     * @return \DSI\Entity\OrganisationProject
     * @throws \DSI\DuplicateEntry
     */
    private function addProjectToOrganisation($project1, $organisation1)
    {
        $organisationProject = new \DSI\Entity\OrganisationProject();
        $organisationProject->setOrganisation($organisation1);
        $organisationProject->setProject($project1);
        $this->organisationProjectRepo->add($organisationProject);
    }

    /**
     * @return \DSI\Entity\Organisation
     */
    private function createOrganisation()
    {
        $organisation1 = new \DSI\Entity\Organisation();
        $organisation1->setOwner($this->user);
        $this->organisationRepo->insert($organisation1);
        return $organisation1;
    }

    /**
     * @return \DSI\Entity\Project
     */
    private function createProject()
    {
        $project1 = new \DSI\Entity\Project();
        $project1->setOwner($this->user);
        $this->projectRepo->insert($project1);
        return $project1;
    }
}