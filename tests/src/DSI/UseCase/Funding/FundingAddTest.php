<?php

require_once __DIR__ . '/../../../../config.php';

use \DSI\Repository;
use \DSI\Entity;
use \DSI\UseCase\Funding\FundingCreate;

class FundingAddTest extends PHPUnit_Framework_TestCase
{
    /** @var Repository\FundingRepo */
    private $fundingRepository;

    /** @var Repository\CountryRepo */
    private $countryRepo;

    /** @var Entity\Country */
    private $country;

    /** @var Repository\FundingSourceRepo */
    private $fundingSourceRepo;

    /** @var Entity\FundingSource */
    private $fundingSource;

    public function setUp()
    {
        $this->fundingRepository = new Repository\FundingRepo();

        $this->countryRepo = new Repository\CountryRepo();
        $this->country = new Entity\Country();
        $this->country->setName('Country Name');
        $this->countryRepo->insert($this->country);

        $this->fundingSourceRepo = new Repository\FundingSourceRepo();
        $this->fundingSource = new Entity\FundingSource();
        $this->fundingSource->setTitle('Funding Source');
        $this->fundingSource->setUrl('http://funding-source.rss');
        $this->fundingSourceRepo->insert($this->fundingSource);
    }

    public function tearDown()
    {
        $this->fundingRepository->clearAll();
        $this->countryRepo->clearAll();
        $this->fundingSourceRepo->clearAll();
    }

    /** @test */
    public function canAddFunding()
    {
        $addFunding = new FundingCreate();
        $addFunding->data()->title = $title = 'Title';
        $addFunding->data()->url = $url = 'http://example.org';
        $addFunding->data()->description = $description = 'Description';
        $addFunding->data()->closingDate = $closingDate = '2016-10-12';
        $addFunding->data()->countryID = $this->country->getId();
        $addFunding->data()->sourceTitle = $this->fundingSource->getTitle();
        $addFunding->exec();

        $this->assertCount(1, $this->fundingRepository->getAll());
    }

    /** @test */
    public function cannotAddWithoutCountryOrFundingSource()
    {
        $e = null;

        $addFunding = new FundingCreate();
        try {
            $addFunding->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('country'));
        $this->assertNotEmpty($e->getTaggedError('fundingSource'));
    }

    /** @test */
    public function cannotAddWithEmptyData()
    {
        $e = null;

        $addFunding = new FundingCreate();
        $addFunding->data()->countryID = $this->country->getId();
        $addFunding->data()->sourceTitle = $this->fundingSource->getTitle();
        try {
            $addFunding->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('title'));
        $this->assertNotEmpty($e->getTaggedError('url'));
        $this->assertNotEmpty($e->getTaggedError('description'));
    }

    /** @test */
    public function canAddWithEmptyClosingDate()
    {
        $e = null;

        $addFunding = new FundingCreate();
        $addFunding->data()->title = $title = 'Title';
        $addFunding->data()->url = $url = 'http://example.org';
        $addFunding->data()->description = $description = 'Description';
        $addFunding->data()->countryID = $this->country->getId();
        $addFunding->data()->sourceTitle = $this->fundingSource->getTitle();
        try {
            $addFunding->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNull($e);
    }

    /** @test */
    public function cannotAddWithInvalidClosingDate()
    {
        $e = null;

        $addFunding = new FundingCreate();
        $addFunding->data()->title = $title = 'Title';
        $addFunding->data()->url = $url = 'http://example.org';
        $addFunding->data()->description = $description = 'Description';
        $addFunding->data()->countryID = $this->country->getId();
        $addFunding->data()->sourceTitle = $this->fundingSource->getTitle();
        $addFunding->data()->closingDate = 'Invalid-2016-date';
        try {
            $addFunding->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('closingDate'));
    }
}