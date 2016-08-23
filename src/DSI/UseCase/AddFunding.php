<?php

namespace DSI\UseCase;

use DSI\Entity\Country;
use DSI\Entity\Funding;
use DSI\Entity\FundingSource;
use DSI\Repository\FundingRepository;
use DSI\Service\ErrorHandler;

class AddFunding
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var AddFunding_Data */
    private $data;

    /** @var FundingRepository */
    private $fundingRepository;

    public function __construct()
    {
        $this->data = new AddFunding_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->fundingRepository = new FundingRepository();

        $this->assertDataHasBeenSubmitted();
        $this->assertDataIsNotEmpty();
        $this->assertClosingDateIsValid();

        $this->saveFunding();
    }

    /**
     * @return AddFunding_Data
     */
    public function data()
    {
        return $this->data;
    }

    private function saveFunding()
    {
        $funding = new Funding();
        $funding->setTitle($this->data()->title);
        $funding->setUrl($this->data()->url);
        $funding->setCountry($this->data()->country);
        $funding->setFundingSource($this->data()->fundingSource);
        $funding->setClosingDate($this->data()->closingDate);
        $funding->setDescription($this->data()->description);
        $this->fundingRepository->insert($funding);
    }

    private function assertDataHasBeenSubmitted()
    {
        if (!$this->data()->country)
            $this->errorHandler->addTaggedError('country', 'Invalid country');
        if (!$this->data()->fundingSource)
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
}

class AddFunding_Data
{
    /** @var string */
    public $title,
        $url,
        $description;

    /** @var Country */
    public $country;

    /** @var FundingSource */
    public $fundingSource;

    /** @var string */
    public $closingDate;
}