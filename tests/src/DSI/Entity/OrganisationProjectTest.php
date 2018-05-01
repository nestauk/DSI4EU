<?php

require_once __DIR__ . '/../../../config.php';

class OrganisationProjectTest extends \PHPUnit_Framework_TestCase
{
    /** @var \DSI\Entity\OrganisationProject */
    private $organisationProject;

    public function setUp()
    {
        $this->organisationProject = new \DSI\Entity\OrganisationProject();
    }

    /** @test setId, getId */
    public function settingDetails_returnsTheDetails()
    {
        $organisation = new \DSI\Entity\Organisation();
        $organisation->setId(1);
        $project = new \DSI\Entity\Project();
        $project->setId(1);

        $this->organisationProject = new \DSI\Entity\OrganisationProject();
        $this->organisationProject->setOrganisation($organisation);
        $this->organisationProject->setProject($project);

        $this->assertEquals($organisation->getId(), $this->organisationProject->getOrganisationID());
        $this->assertEquals($organisation->getId(), $this->organisationProject->getOrganisation()->getId());
        $this->assertEquals($project->getId(), $this->organisationProject->getProjectID());
        $this->assertEquals($project->getId(), $this->organisationProject->getProject()->getId());
    }
}