<?php

namespace DSI\UseCase;

use DSI\Entity\CountryRegion;
use DSI\Entity\ReportProfile;
use DSI\Entity\User;
use DSI\Repository\CountryRegionRepository;
use DSI\Repository\CountryRepository;
use DSI\Repository\ReportProfileRepository;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class CreateReportProfile
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ReportProfile */
    private $report;

    /** @var ReportProfileRepository */
    private $reportProfileRepo;

    /** @var CreateReportProfile_Data */
    private $data;

    public function __construct()
    {
        $this->data = new CreateReportProfile_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->reportProfileRepo = new ReportProfileRepository();

        if(!$this->data()->executor)
            throw new \InvalidArgumentException('Invalid executor');
        if(!$this->data()->byUserId)
            throw new \InvalidArgumentException('Invalid reporter user');
        if(!$this->data()->reportedUserId)
            throw new \InvalidArgumentException('Invalid reported user');

        $this->report = new ReportProfile();
        $this->report->setByUser((new UserRepository())->getById($this->data()->byUserId));
        $this->report->setReportedUser((new UserRepository())->getById($this->data()->reportedUserId));
        $this->report->setComment($this->data()->comment);

        $this->reportProfileRepo->insert($this->report);
    }

    /**
     * @return CreateReportProfile_Data
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return CountryRegion
     */
    public function getReport()
    {
        return $this->report;
    }
}

class CreateReportProfile_Data
{
    /** @var User */
    public $executor;

    /** @var int */
    public $byUserId,
        $reportedUserId;

    /** @var string */
    public $comment;
}