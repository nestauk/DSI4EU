<?php

require_once __DIR__ . '/../../../../config.php';

use \DSI\Repository;
use \DSI\Entity;
use \DSI\UseCase\Funding\FundingEdit;

class FundingEditTest extends PHPUnit_Framework_TestCase
{
    /** @var Repository\FundingRepository */
    private $fundingRepository;

    /** @var Entity\Funding */
    private $funding;

    /** @var Repository\CountryRepository */
    private $countryRepo;

    /** @var Entity\Country */
    private $country;

    /** @var Repository\FundingSourceRepository */
    private $fundingSourceRepo;

    /** @var Entity\FundingSource */
    private $fundingSource;

    public function setUp()
    {
        $this->fundingRepository = new Repository\FundingRepository();

        $this->createCountry();
        $this->createSource();
        $this->createFunding();
    }

    public function tearDown()
    {
        $this->fundingRepository->clearAll();
        $this->countryRepo->clearAll();
        $this->fundingSourceRepo->clearAll();
    }

    /** @test */
    public function cannotEditWithoutFundingOrCountryOrFundingSource()
    {
        $e = null;

        $exec = new FundingEdit();
        try {
            $exec->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('funding'));
        $this->assertNotEmpty($e->getTaggedError('country'));
        $this->assertNotEmpty($e->getTaggedError('fundingSource'));
    }

    /** @test */
    public function cannotEditWithEmptyData()
    {
        $e = null;

        $exec = new FundingEdit();
        $exec->data()->funding = $this->funding;
        $exec->data()->countryID = $this->country->getId();
        $exec->data()->sourceTitle = $this->fundingSource->getTitle();
        try {
            $exec->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('title'));
        $this->assertNotEmpty($e->getTaggedError('url'));
        $this->assertNotEmpty($e->getTaggedError('description'));
    }

    /** @test */
    public function canEditWithEmptyClosingDate()
    {
        $e = null;

        $exec = new FundingEdit();
        $exec->data()->funding = $this->funding;
        $exec->data()->title = $title = 'Title';
        $exec->data()->url = $url = 'http://example.org';
        $exec->data()->description = $description = 'Description';
        $exec->data()->countryID = $this->country->getId();
        $exec->data()->sourceTitle = $this->fundingSource->getTitle();
        try {
            $exec->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNull($e);
    }

    /** @test */
    public function cannotEditWithInvalidClosingDate()
    {
        $e = null;

        $exec = new FundingEdit();
        $exec->data()->funding = $this->funding;
        $exec->data()->title = $title = 'Title';
        $exec->data()->url = $url = 'http://example.org';
        $exec->data()->description = $description = 'Description';
        $exec->data()->countryID = $this->country->getId();
        $exec->data()->sourceTitle = $this->fundingSource->getTitle();
        $exec->data()->closingDate = 'Invalid-2016-date';
        try {
            $exec->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('closingDate'));
    }

    /** @test */
    public function canEditFunding()
    {
        $editFunding = new FundingEdit();
        $editFunding->data()->funding = $this->funding;
        $editFunding->data()->title = $title = 'Title';
        $editFunding->data()->url = $url = 'http://example.org';
        $editFunding->data()->description = $description = 'Description';
        $editFunding->data()->closingDate = $closingDate = '2016-10-12';
        $editFunding->data()->countryID = $this->country->getId();
        $editFunding->data()->sourceTitle = $this->fundingSource->getTitle();
        $editFunding->exec();

        $funding = $this->fundingRepository->getById($this->funding->getId());
        $this->assertEquals($title, $funding->getTitle());
        $this->assertEquals($url, $funding->getUrl());
        $this->assertEquals($description, $funding->getDescription());
        $this->assertEquals($closingDate, $funding->getClosingDate());
        $this->assertEquals($this->country->getId(), $funding->getCountryID());
        $this->assertEquals($this->fundingSource->getId(), $funding->getSourceID());
    }


    private function createCountry()
    {
        $this->countryRepo = new Repository\CountryRepository();
        $this->country = new Entity\Country();
        $this->country->setName('Country Name');
        $this->countryRepo->insert($this->country);
    }

    private function createSource()
    {
        $this->fundingSourceRepo = new Repository\FundingSourceRepository();
        $this->fundingSource = new Entity\FundingSource();
        $this->fundingSource->setTitle('Funding Source');
        $this->fundingSource->setUrl('http://funding-source.rss');
        $this->fundingSourceRepo->insert($this->fundingSource);
    }

    private function createFunding()
    {
        $this->funding = new Entity\Funding();
        $this->fundingRepository->insert($this->funding);
    }
}