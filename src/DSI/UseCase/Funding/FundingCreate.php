<?php

namespace DSI\UseCase\Funding;

use DSI\Entity\Funding;
use DSI\Entity\FundingSource;
use DSI\NotFound;
use DSI\Repository\CountryRepo;
use DSI\Repository\FundingRepo;
use DSI\Repository\FundingSourceRepo;
use DSI\Repository\FundingTargetRepo;
use DSI\Repository\FundingTypeRepo;
use DSI\Service\ErrorHandler;

class FundingCreate
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var FundingCreate_Data */
    private $data;

    /** @var FundingRepo */
    private $fundingRepository;

    /** @var FundingSource */
    private $fundingSource;

    public function __construct()
    {
        $this->data = new FundingCreate_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->fundingRepository = new FundingRepo();

        $this->assertDataHasBeenSubmitted();
        $this->assertDataIsNotEmpty();
        $this->assertClosingDateIsValid();
        $this->getFundingSource();

        $this->saveFunding();
    }

    /**
     * @return FundingCreate_Data
     */
    public function data()
    {
        return $this->data;
    }

    private function saveFunding()
    {
        $fundingTargetRepository = new FundingTargetRepo();

        $funding = new Funding();
        $funding->setTitle($this->data()->title);
        $funding->setUrl($this->data()->url);
        if ($this->data()->typeID)
            $funding->setType((new FundingTypeRepo())->getById($this->data()->typeID));
        $funding->setCountry((new CountryRepo())->getById($this->data()->countryID));
        if ($this->fundingSource)
            $funding->setSource($this->fundingSource);
        foreach ((array)$this->data()->targets AS $targetID)
            $funding->addTarget($fundingTargetRepository->getById((int)$targetID));

        $funding->setClosingDate($this->data()->closingDate);
        $funding->setDescription($this->data()->description);
        $this->fundingRepository->insert($funding);
    }

    private function assertDataHasBeenSubmitted()
    {
        if (!$this->data()->countryID)
            $this->errorHandler->addTaggedError('country', 'Invalid country');
        if (!$this->data()->sourceTitle)
            $this->errorHandler->addTaggedError('fundingSource', 'Invalid funding source');

        $this->errorHandler->throwIfNotEmpty();
    }

    private function assertDataIsNotEmpty()
    {
        if (!$this->data()->title OR $this->data()->title == '')
            $this->errorHandler->addTaggedError('title', 'Please specify a title');
        if (!$this->data()->url OR $this->data()->url == '')
            $this->errorHandler->addTaggedError('url', 'Please specify the url');
        if (!$this->data()->description OR $this->data()->description == '')
            $this->errorHandler->addTaggedError('description', 'Please specify a desription');

        $this->errorHandler->throwIfNotEmpty();
    }

    private function assertClosingDateIsValid()
    {
        if ($this->data()->closingDate AND $this->data()->closingDate != '')
            if (!preg_match('<^\d{4}\-\d{2}\-\d{2}$>', $this->data()->closingDate))
                $this->errorHandler->addTaggedError('closingDate', 'Please specify a valid date');

        $this->errorHandler->throwIfNotEmpty();
    }

    private function getFundingSource()
    {
        if ($this->data()->sourceTitle) {
            $fundingSourceRepository = new FundingSourceRepo();
            try {
                $this->fundingSource = $fundingSourceRepository->getByTitle($this->data()->sourceTitle);
            } catch (NotFound $e) {
                $this->fundingSource = new FundingSource();
                $this->fundingSource->setTitle($this->data()->sourceTitle);
                $fundingSourceRepository->insert($this->fundingSource);
            }
        }
    }
}

class FundingCreate_Data
{
    /** @var string */
    public $title,
        $url,
        $description;

    /** @var int */
    public $countryID,
        $typeID;

    /** @var int[] */
    public $targets;

    /** @var string */
    public $sourceTitle;

    /** @var string */
    public $closingDate;
}