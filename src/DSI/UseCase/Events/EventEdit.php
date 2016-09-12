<?php

namespace DSI\UseCase\Funding;

use DSI\Entity\Funding;
use DSI\Entity\FundingSource;
use DSI\NotFound;
use DSI\Repository\CountryRepository;
use DSI\Repository\FundingRepository;
use DSI\Repository\FundingSourceRepository;
use DSI\Service\ErrorHandler;

class EventEdit
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var EditFunding_Data */
    private $data;

    /** @var FundingRepository */
    private $fundingRepository;

    /** @var FundingSource */
    private $fundingSource;

    public function __construct()
    {
        $this->data = new EditFunding_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->fundingRepository = new FundingRepository();

        $this->assertDataHasBeenSubmitted();
        $this->assertDataIsNotEmpty();
        $this->assertClosingDateIsValid();
        $this->getFundingSource();

        $this->saveFunding();
    }

    /**
     * @return EditFunding_Data
     */
    public function data()
    {
        return $this->data;
    }

    private function saveFunding()
    {
        $this->data()->funding->setTitle($this->data()->title);
        $this->data()->funding->setUrl($this->data()->url);
        $this->data()->funding->setCountry((new CountryRepository())->getById($this->data()->countryID));
        $this->data()->funding->setSource($this->fundingSource);
        $this->data()->funding->setClosingDate($this->data()->closingDate);
        $this->data()->funding->setDescription($this->data()->description);
        $this->fundingRepository->save($this->data()->funding);
    }

    private function assertDataHasBeenSubmitted()
    {
        if (!$this->data()->funding)
            $this->errorHandler->addTaggedError('funding', 'Invalid funding');
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
            $fundingSourceRepository = new FundingSourceRepository();
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

class EditFunding_Data
{
    /** @var Funding */
    public $funding;

    /** @var string */
    public $title,
        $url,
        $description;

    /** @var int */
    public $countryID;

    /** @var string */
    public $sourceTitle;

    /** @var string */
    public $closingDate;
}