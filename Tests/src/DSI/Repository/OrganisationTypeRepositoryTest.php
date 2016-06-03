<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository\OrganisationTypeRepository;
use \DSI\Entity\OrganisationType;

class OrganisationTypeRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var OrganisationTypeRepository */
    private $organisationTypeRepo;

    public function setUp()
    {
        $this->organisationTypeRepo = new OrganisationTypeRepository();
    }

    public function tearDown()
    {
        $this->organisationTypeRepo->clearAll();
    }

    /** @test save, getByID */
    public function organisationTypeCanBeSaved()
    {
        $organisationType = new OrganisationType();
        $this->organisationTypeRepo->insert($organisationType);
        $this->assertGreaterThan(0, $organisationType->getId());
    }

    /** @test save, getByID */
    public function organisationTypeCanBeUpdated()
    {
        $organisationType = new OrganisationType();
        $this->organisationTypeRepo->insert($organisationType);

        $organisationType->setName('test');
        $this->organisationTypeRepo->save($organisationType);

        $sameOrgType = $this->organisationTypeRepo->getById($organisationType->getId());
        $this->assertEquals($organisationType->getName(), $sameOrgType->getName());
    }

    /** @test getByID */
    public function gettingAnNonExistentOrganisationTypeById_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->organisationTypeRepo->getById(1);
    }

    /** @test save */
    public function NonexistentOrganisationTypeCannotBeSaved()
    {
        $organisationType = new OrganisationType();
        $organisationType->setId(1);
        $this->setExpectedException(\DSI\NotFound::class);
        $this->organisationTypeRepo->save($organisationType);
    }

    /** @test getAll */
    public function getAllOrganisationTypes()
    {
        $organisationType = new OrganisationType();
        $this->organisationTypeRepo->insert($organisationType);

        $this->assertCount(1, $this->organisationTypeRepo->getAll());

        $organisationType = new OrganisationType();
        $this->organisationTypeRepo->insert($organisationType);

        $this->assertCount(2, $this->organisationTypeRepo->getAll());
    }

    /** @test saveAsNew getById */
    public function setAllOrganisationDetails()
    {
        $organisationType = new OrganisationType();
        $organisationType->setName('Name');
        $this->organisationTypeRepo->insert($organisationType);

        $sameOrganisation = $this->organisationTypeRepo->getById($organisationType->getId());
        $this->assertEquals($organisationType->getName(), $sameOrganisation->getName());
    }
}