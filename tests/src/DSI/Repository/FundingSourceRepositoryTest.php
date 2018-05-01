<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository\FundingSourceRepo;
use \DSI\Entity\FundingSource;

class FundingSourceRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var FundingSourceRepo */
    private $fundingSourceRepository;

    public function setUp()
    {
        $this->fundingSourceRepository = new FundingSourceRepo();
    }

    public function tearDown()
    {
        $this->fundingSourceRepository->clearAll();
    }

    /** @test saveAsNew */
    public function fundingSourceCanBeCreated()
    {
        $fundingSource = new FundingSource();
        $fundingSource->setTitle($title = 'Title');
        $fundingSource->setUrl($url = 'http://example.org');
        $this->fundingSourceRepository->insert($fundingSource);

        $this->assertEquals(1, $fundingSource->getId());
        $fundingSource = $this->fundingSourceRepository->getById($fundingSource->getId());
        $this->assertEquals($title, $fundingSource->getTitle());
        $this->assertEquals($url, $fundingSource->getUrl());
    }

    /** @test getByID */
    public function gettingAnNonExistentObjectById_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->fundingSourceRepository->getById(1);
    }

    /** @test save */
    public function NonexistentObjectCannotBeSaved()
    {
        $fundingSource = new FundingSource();
        $fundingSource->setId(1);

        $this->setExpectedException(\DSI\NotFound::class);
        $this->fundingSourceRepository->save($fundingSource);
    }

    /** @test getAll */
    public function getAll()
    {
        $fundingSource = new FundingSource();
        $this->fundingSourceRepository->insert($fundingSource);

        $this->assertCount(1, $this->fundingSourceRepository->getAll());

        $fundingSource = new FundingSource();
        $this->fundingSourceRepository->insert($fundingSource);

        $this->assertCount(2, $this->fundingSourceRepository->getAll());
    }

    /** @test */
    public function objectCanBeUpdated()
    {
        $fundingSource = new FundingSource();
        $this->fundingSourceRepository->insert($fundingSource);

        $fundingSource->setTitle($title = 'Title');
        $fundingSource->setUrl($url = 'http://example.org');
        $this->fundingSourceRepository->save($fundingSource);

        $fundingSource = $this->fundingSourceRepository->getById($fundingSource->getId());
        $this->assertEquals($title, $fundingSource->getTitle());
        $this->assertEquals($url, $fundingSource->getUrl());
    }
}