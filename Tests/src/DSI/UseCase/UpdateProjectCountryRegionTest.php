<?php

require_once __DIR__ . '/../../../config.php';

use DSI\Service\ErrorHandler;

class UpdateProjectCountryRegionTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Entity\CountryRegion */
    private $countryRegion;
    /** @var \DSI\Entity\Country */
    private $country;
    /** @var \DSI\Repository\CountryRegionRepository */
    private $countryRegionRepository;
    /** @var \DSI\Repository\CountryRepository */
    private $countryRepository;
    /** @var \DSI\UseCase\UpdateProjectCountryRegion */
    private $updateProjectCountryRegionCmd;

    /** @var \DSI\Repository\ProjectRepository */
    private $projectRepo;

    /** @var \DSI\Entity\Project */
    private $project;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user;

    public function setUp()
    {
        $this->updateProjectCountryRegionCmd = new \DSI\UseCase\UpdateProjectCountryRegion();
        $this->userRepo = new \DSI\Repository\UserRepository();
        $this->countryRepository = new \DSI\Repository\CountryRepository();
        $this->countryRegionRepository = new \DSI\Repository\CountryRegionRepository();

        $this->country = new \DSI\Entity\Country();
        $this->country->setName('test');
        $this->countryRepository->saveAsNew($this->country);

        $this->countryRegion = new \DSI\Entity\CountryRegion();
        $this->countryRegion->setName('test');
        $this->countryRegion->setCountry($this->country);
        $this->countryRegionRepository->saveAsNew($this->countryRegion);

        $this->user = new \DSI\Entity\User();
        $this->userRepo->insert($this->user);

        $this->projectRepo = new \DSI\Repository\ProjectRepository();
        $this->project = new \DSI\Entity\Project();
        $this->project->setOwner($this->user);
        $this->projectRepo->insert($this->project);
    }

    public function tearDown()
    {
        $this->projectRepo->clearAll();
        $this->userRepo->clearAll();
        $this->countryRegionRepository->clearAll();
        $this->countryRepository->clearAll();
    }

    /** @test */
    public function successfulUpdateWithExistingRegion()
    {
        try {
            $this->updateProjectCountryRegionCmd->data()->projectID = $this->project->getId();
            $this->updateProjectCountryRegionCmd->data()->countryID = $this->country->getId();
            $this->updateProjectCountryRegionCmd->data()->region = $this->countryRegion->getName();
            $this->updateProjectCountryRegionCmd->exec();
        } catch (ErrorHandler $e) {
            $this->assertNull($e);
        }

        $project = $this->projectRepo->getById($this->project->getId());
        $this->assertEquals($this->country->getId(), $project->getCountryID());
        $this->assertEquals($this->countryRegion->getId(), $project->getCountryRegionID());
    }

    /** @test */
    public function successfulUpdateWithNonexistentRegion_CreatesNewRegion()
    {
        $this->assertCount(1, $this->countryRegionRepository->getAll());

        try {
            $this->updateProjectCountryRegionCmd->data()->projectID = $this->project->getId();
            $this->updateProjectCountryRegionCmd->data()->countryID = $this->country->getId();
            $this->updateProjectCountryRegionCmd->data()->region = 'new region name';
            $this->updateProjectCountryRegionCmd->exec();
        } catch (ErrorHandler $e) {
            $this->assertNull($e);
        }

        $project = $this->projectRepo->getById($this->project->getId());
        $this->assertEquals($this->country->getId(), $project->getCountryID());

        $this->assertCount(2, $this->countryRegionRepository->getAll());
    }

    /** @test */
    public function cannotExecWithoutAProjectID()
    {
        $e = null;
        try {
            $this->updateProjectCountryRegionCmd->data()->countryID = $this->country->getId();
            $this->updateProjectCountryRegionCmd->data()->region = $this->countryRegion->getId();
            $this->updateProjectCountryRegionCmd->exec();
        } catch (ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('project'));
    }

    /** @test */
    public function cannotExecWithoutARegionName()
    {
        $e = null;
        try {
            $this->updateProjectCountryRegionCmd->data()->projectID = $this->project->getId();
            $this->updateProjectCountryRegionCmd->data()->countryID = $this->country->getId();
            $this->updateProjectCountryRegionCmd->exec();
        } catch (ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('region'));
    }

    /** @test */
    public function cannotExecWithoutACountry()
    {
        $e = null;
        try {
            $this->updateProjectCountryRegionCmd->data()->projectID = $this->project->getId();
            $this->updateProjectCountryRegionCmd->data()->region = $this->countryRegion->getId();
            $this->updateProjectCountryRegionCmd->exec();
        } catch (ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('country'));
    }
}