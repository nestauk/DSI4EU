<?php

namespace DSI\UseCase;

use DSI\Entity\CountryRegion;
use DSI\Entity\ReportProfile;
use DSI\Entity\User;
use DSI\Repository\ReportProfileRepo;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;

class CreateReportProfile
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ReportProfile */
    private $report;

    /** @var ReportProfileRepo */
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
        $this->reportProfileRepo = new ReportProfileRepo();

        if(!$this->data()->executor)
            throw new \InvalidArgumentException('Invalid executor');
        if(!$this->data()->byUserId)
            throw new \InvalidArgumentException('Invalid reporter user');
        if(!$this->data()->reportedUserId)
            throw new \InvalidArgumentException('Invalid reported user');

        $this->report = new ReportProfile();
        $this->report->setByUser((new UserRepo())->getById($this->data()->byUserId));
        $this->report->setReportedUser((new UserRepo())->getById($this->data()->reportedUserId));
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
     * @return ReportProfile
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