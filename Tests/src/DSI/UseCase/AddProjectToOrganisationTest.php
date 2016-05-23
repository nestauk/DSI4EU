<?php

require_once __DIR__ . '/../../../config.php';

class AddProjectToOrganisationTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\AddProjectToOrganisation */
    private $addProjectToOrganisation;

    /** @var \DSI\Repository\OrganisationProjectRepository */
    private $organisationProjectRepo;

    /** @var \DSI\Repository\OrganisationRepository */
    private $organisationRepo;

    /** @var \DSI\Entity\Organisation */
    private $organisation;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Repository\ProjectRepository */
    private $projectRepo;

    /** @var \DSI\Entity\Project */
    private $project_1, $project_2;

    public function setUp()
    {
        $this->organisationProjectRepo = new \DSI\Repository\OrganisationProjectRepository();
        $this->organisationRepo = new \DSI\Repository\OrganisationRepository();
        $this->projectRepo = new \DSI\Repository\ProjectRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();

        $this->addProjectToOrganisation = new \DSI\UseCase\AddProjectToOrganisation();

        $user = new \DSI\Entity\User();
        $this->userRepo->insert($user);

        $this->project_1 = new \DSI\Entity\Project();
        $this->project_1->setOwner($user);
        $this->projectRepo->insert($this->project_1);
        $this->project_2 = new \DSI\Entity\Project();
        $this->project_2->setOwner($user);
        $this->projectRepo->insert($this->project_2);

        $this->organisation = new \DSI\Entity\Organisation();
        $this->organisation->setOwner($user);
        $this->organisationRepo->insert($this->organisation);
    }

    public function tearDown()
    {
        $this->organisationProjectRepo->clearAll();
        $this->organisationRepo->clearAll();
        $this->projectRepo->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test */
    public function successfulAdditionOfProjectToOrganisation()
    {
        $this->addProjectToOrganisation->data()->projectID = $this->project_2->getId();
        $this->addProjectToOrganisation->data()->organisationID = $this->organisation->getId();
        $this->addProjectToOrganisation->exec();

        $this->assertTrue(
            $this->organisationProjectRepo->organisationHasProject(
                $this->addProjectToOrganisation->data()->organisationID,
                $this->addProjectToOrganisation->data()->projectID
            )
        );
    }

    /** @test */
    public function cannotAddSameProjectTwice()
    {
        $e = null;
        $this->addProjectToOrganisation->data()->projectID = $this->project_2->getId();
        $this->addProjectToOrganisation->data()->organisationID = $this->organisation->getId();
        $this->addProjectToOrganisation->exec();

        try {
            $this->addProjectToOrganisation->data()->projectID = $this->project_2->getId();
            $this->addProjectToOrganisation->data()->organisationID = $this->organisation->getId();
            $this->addProjectToOrganisation->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('project'));
    }
}