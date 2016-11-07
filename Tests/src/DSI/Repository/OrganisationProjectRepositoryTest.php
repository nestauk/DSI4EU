<?php

require_once __DIR__ . '/../../../config.php';

class OrganisationProjectsRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Repository\OrganisationProjectRepository */
    protected $organisationProjectsRepo;

    /** @var \DSI\Repository\OrganisationRepository */
    protected $organisationsRepo;

    /** @var \DSI\Repository\UserRepository */
    protected $usersRepo;

    /** @var \DSI\Entity\Organisation */
    protected $organisation_1, $organisation_2, $organisation_3;

    /** @var \DSI\Repository\ProjectRepository */
    protected $projectsRepo;

    /** @var \DSI\Entity\Project */
    protected $project_1, $project_2, $project_3;

    public function setUp()
    {
        $this->organisationProjectsRepo = new \DSI\Repository\OrganisationProjectRepository();
        $this->organisationsRepo = new \DSI\Repository\OrganisationRepository();
        $this->projectsRepo = new \DSI\Repository\ProjectRepository();
        $this->usersRepo = new \DSI\Repository\UserRepository();

        $this->organisation_1 = $this->createOrganisation(1);
        $this->organisation_2 = $this->createOrganisation(2);
        $this->organisation_3 = $this->createOrganisation(3);
        $this->project_1 = $this->createProject(1);
        $this->project_2 = $this->createProject(2);
        $this->project_3 = $this->createProject(3);
    }

    public function tearDown()
    {
        $this->organisationProjectsRepo->clearAll();
        $this->organisationsRepo->clearAll();
        $this->projectsRepo->clearAll();
        $this->usersRepo->clearAll();
    }

    /** @test saveAsNew */
    public function organisationProjectCanBeSaved()
    {
        $organisationProject = new \DSI\Entity\OrganisationProject();
        $organisationProject->setOrganisation($this->organisation_1);
        $organisationProject->setProject($this->project_1);
        $this->organisationProjectsRepo->add($organisationProject);

        $organisationProject = new \DSI\Entity\OrganisationProject();
        $organisationProject->setOrganisation($this->organisation_1);
        $organisationProject->setProject($this->project_2);
        $this->organisationProjectsRepo->add($organisationProject);

        $organisationProject = new \DSI\Entity\OrganisationProject();
        $organisationProject->setOrganisation($this->organisation_2);
        $organisationProject->setProject($this->project_1);
        $this->organisationProjectsRepo->add($organisationProject);

        $organisationProject = new \DSI\Entity\OrganisationProject();
        $organisationProject->setOrganisation($this->organisation_3);
        $organisationProject->setProject($this->project_1);
        $this->organisationProjectsRepo->add($organisationProject);

        $this->assertCount(2, $this->organisationProjectsRepo->getByOrganisationID(1));
    }

    /** @test saveAsNew */
    public function cannotAddSameOrganisationProjectTwice()
    {
        $organisationProject = new \DSI\Entity\OrganisationProject();
        $organisationProject->setOrganisation($this->organisation_1);
        $organisationProject->setProject($this->project_1);
        $this->organisationProjectsRepo->add($organisationProject);

        $organisationProject = new \DSI\Entity\OrganisationProject();
        $organisationProject->setOrganisation($this->organisation_1);
        $organisationProject->setProject($this->project_1);
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->organisationProjectsRepo->add($organisationProject);
    }

    /** @test saveAsNew */
    public function getAllProjectIDsForOrganisation()
    {
        $organisationProject = new \DSI\Entity\OrganisationProject();
        $organisationProject->setOrganisation($this->organisation_1);
        $organisationProject->setProject($this->project_1);
        $this->organisationProjectsRepo->add($organisationProject);

        $organisationProject = new \DSI\Entity\OrganisationProject();
        $organisationProject->setOrganisation($this->organisation_1);
        $organisationProject->setProject($this->project_2);
        $this->organisationProjectsRepo->add($organisationProject);

        $this->assertEquals([1, 2], $this->organisationProjectsRepo->getProjectIDsForOrganisation(1));
    }

    /** @test saveAsNew */
    public function getAllOrganisationsForProject()
    {
        $organisationProject = new \DSI\Entity\OrganisationProject();
        $organisationProject->setOrganisation($this->organisation_1);
        $organisationProject->setProject($this->project_1);
        $this->organisationProjectsRepo->add($organisationProject);

        $organisationProject = new \DSI\Entity\OrganisationProject();
        $organisationProject->setOrganisation($this->organisation_2);
        $organisationProject->setProject($this->project_1);
        $this->organisationProjectsRepo->add($organisationProject);

        $this->assertCount(2, $this->organisationProjectsRepo->getByProjectID(1));
    }

    /** @test saveAsNew */
    public function getAllOrganisationIDsForProject()
    {
        $this->assertEquals([], $this->organisationProjectsRepo->getOrganisationIDsForProject(
            $this->project_1
        ));

        $organisationProject = new \DSI\Entity\OrganisationProject();
        $organisationProject->setOrganisation($this->organisation_1);
        $organisationProject->setProject($this->project_1);
        $this->organisationProjectsRepo->add($organisationProject);

        $this->assertEquals([1], $this->organisationProjectsRepo->getOrganisationIDsForProject(
            $this->project_1
        ));

        $organisationProject = new \DSI\Entity\OrganisationProject();
        $organisationProject->setOrganisation($this->organisation_2);
        $organisationProject->setProject($this->project_1);
        $this->organisationProjectsRepo->add($organisationProject);

        $this->assertEquals([1, 2], $this->organisationProjectsRepo->getOrganisationIDsForProject(
            $this->project_1
        ));

        $organisationProject = new \DSI\Entity\OrganisationProject();
        $organisationProject->setOrganisation($this->organisation_1);
        $organisationProject->setProject($this->project_1);
        $this->organisationProjectsRepo->remove($organisationProject);

        $this->assertEquals([2], $this->organisationProjectsRepo->getOrganisationIDsForProject(
            $this->project_1
        ));
    }

    /** @test saveAsNew */
    public function cannotRemoveNonexistentObject()
    {
        $organisationProject = new \DSI\Entity\OrganisationProject();
        $organisationProject->setOrganisation($this->organisation_1);
        $organisationProject->setProject($this->project_1);
        $this->setExpectedException(\DSI\NotFound::class);
        $this->organisationProjectsRepo->remove($organisationProject);
    }

    /** @test saveAsNew */
    public function canCheckIfOrganisationHasProjectName()
    {
        $organisationProject = new \DSI\Entity\OrganisationProject();
        $organisationProject->setOrganisation($this->organisation_1);
        $organisationProject->setProject($this->project_1);
        $this->organisationProjectsRepo->add($organisationProject);

        $organisationProject = new \DSI\Entity\OrganisationProject();
        $organisationProject->setOrganisation($this->organisation_2);
        $organisationProject->setProject($this->project_2);
        $this->organisationProjectsRepo->add($organisationProject);

        $this->assertTrue($this->organisationProjectsRepo->organisationHasProject(
            $this->organisation_1->getId(), $this->project_1->getId())
        );
        $this->assertFalse($this->organisationProjectsRepo->organisationHasProject(
            $this->organisation_1->getId(), $this->project_2->getId())
        );
        $this->assertTrue($this->organisationProjectsRepo->organisationHasProject(
            $this->organisation_2->getId(), $this->project_2->getId())
        );
        $this->assertFalse($this->organisationProjectsRepo->organisationHasProject(
            $this->organisation_2->getId(), $this->project_1->getId())
        );
    }

    /** @test saveAsNew */
    public function getByProjectIDs()
    {
        $organisationProject = new \DSI\Entity\OrganisationProject();
        $organisationProject->setOrganisation($this->organisation_1);
        $organisationProject->setProject($this->project_1);
        $this->organisationProjectsRepo->add($organisationProject);

        $organisationProject = new \DSI\Entity\OrganisationProject();
        $organisationProject->setOrganisation($this->organisation_2);
        $organisationProject->setProject($this->project_2);
        $this->organisationProjectsRepo->add($organisationProject);

        $organisationProject = new \DSI\Entity\OrganisationProject();
        $organisationProject->setOrganisation($this->organisation_1);
        $organisationProject->setProject($this->project_2);
        $this->organisationProjectsRepo->add($organisationProject);

        $this->assertCount(0, $this->organisationProjectsRepo->getByProjectIDs([

        ]));

        $this->assertCount(1, $this->organisationProjectsRepo->getByProjectIDs([
            $this->project_1->getId()
        ]));

        $this->assertCount(2, $this->organisationProjectsRepo->getByProjectIDs([
            $this->project_2->getId()
        ]));

        $this->assertCount(3, $this->organisationProjectsRepo->getByProjectIDs([
            $this->project_1->getId(), $this->project_2->getId()
        ]));
    }

    /** @test saveAsNew */
    public function canGetPartnerOrganisations()
    {
        $organisationProject = new \DSI\Entity\OrganisationProject();
        $organisationProject->setOrganisation($this->organisation_1);
        $organisationProject->setProject($this->project_1);
        $this->organisationProjectsRepo->add($organisationProject);

        $this->assertCount(0, $this->organisationProjectsRepo->getPartnerOrganisationsFor($this->organisation_1));

        $organisationProject = new \DSI\Entity\OrganisationProject();
        $organisationProject->setOrganisation($this->organisation_2);
        $organisationProject->setProject($this->project_1);
        $this->organisationProjectsRepo->add($organisationProject);

        $this->assertCount(1, $this->organisationProjectsRepo->getPartnerOrganisationsFor($this->organisation_1));

        $organisationProject = new \DSI\Entity\OrganisationProject();
        $organisationProject->setOrganisation($this->organisation_3);
        $organisationProject->setProject($this->project_1);
        $this->organisationProjectsRepo->add($organisationProject);

        $this->assertCount(2, $this->organisationProjectsRepo->getPartnerOrganisationsFor($this->organisation_1));

        $organisationProject = new \DSI\Entity\OrganisationProject();
        $organisationProject->setOrganisation($this->organisation_1);
        $organisationProject->setProject($this->project_2);
        $this->organisationProjectsRepo->add($organisationProject);

        $this->assertCount(2, $this->organisationProjectsRepo->getPartnerOrganisationsFor($this->organisation_1));

        $organisationProject = new \DSI\Entity\OrganisationProject();
        $organisationProject->setOrganisation($this->organisation_2);
        $organisationProject->setProject($this->project_2);
        $this->organisationProjectsRepo->add($organisationProject);

        $this->assertCount(2, $this->organisationProjectsRepo->getPartnerOrganisationsFor($this->organisation_1));
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

    private function createProject(int $projectID)
    {
        $user = new \DSI\Entity\User();
        $this->usersRepo->insert($user);

        $project = new \DSI\Entity\Project();
        $project->setId($projectID);
        $project->setOwner($user);
        $this->projectsRepo->insert($project);
        return $project;
    }
}