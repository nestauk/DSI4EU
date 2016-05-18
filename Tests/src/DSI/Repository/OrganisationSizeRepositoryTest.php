<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository\OrganisationSizeRepository;
use \DSI\Entity\OrganisationSize;

class OrganisationSizeRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var OrganisationSizeRepository */
    private $organisationSizeRepo;

    public function setUp()
    {
        $this->organisationSizeRepo = new OrganisationSizeRepository();
    }

    public function tearDown()
    {
        $this->organisationSizeRepo->clearAll();
    }

    /** @test save, getByID */
    public function organisationSizeCanBeSaved()
    {
        $organisationSize = new OrganisationSize();
        $this->organisationSizeRepo->saveAsNew($organisationSize);
        $this->assertGreaterThan(0, $organisationSize->getId());
    }

    /** @test save, getByID */
    public function organisationSizeCanBeUpdated()
    {
        $organisationSize = new OrganisationSize();
        $this->organisationSizeRepo->saveAsNew($organisationSize);

        $organisationSize->setName('test');
        $this->organisationSizeRepo->save($organisationSize);

        $sameOrgSize = $this->organisationSizeRepo->getById($organisationSize->getId());
        $this->assertEquals($organisationSize->getName(), $sameOrgSize->getName());
    }

    /** @test getByID */
    public function gettingAnNonExistentOrganisationSizeById_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->organisationSizeRepo->getById(1);
    }

    /** @test save */
    public function NonexistentOrganisationSizeCannotBeSaved()
    {
        $organisationSize = new OrganisationSize();
        $organisationSize->setId(1);
        $this->setExpectedException(\DSI\NotFound::class);
        $this->organisationSizeRepo->save($organisationSize);
    }

    /** @test getAll */
    public function getAllOrganisationSizes()
    {
        $organisationSize = new OrganisationSize();
        $this->organisationSizeRepo->saveAsNew($organisationSize);

        $this->assertCount(1, $this->organisationSizeRepo->getAll());

        $organisationSize = new OrganisationSize();
        $this->organisationSizeRepo->saveAsNew($organisationSize);

        $this->assertCount(2, $this->organisationSizeRepo->getAll());
    }

    /** @test saveAsNew getById */
    public function setAllOrganisationDetails()
    {
        $organisationSize = new OrganisationSize();
        $organisationSize->setName('Name');
        $this->organisationSizeRepo->saveAsNew($organisationSize);

        $sameOrganisation = $this->organisationSizeRepo->getById($organisationSize->getId());
        $this->assertEquals($organisationSize->getName(), $sameOrganisation->getName());
    }
}