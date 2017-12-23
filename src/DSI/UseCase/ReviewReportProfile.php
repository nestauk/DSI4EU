<?php

namespace DSI\UseCase;

use DSI\Entity\CountryRegion;
use DSI\Entity\ReportProfile;
use DSI\Entity\User;
use DSI\Repository\ReportProfileRepo;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;

class ReviewReportProfile
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ReportProfile */
    private $report;

    /** @var ReportProfileRepo */
    private $reportProfileRepo;

    /** @var ReviewReportProfile_Data */
    private $data;

    public function __construct()
    {
        $this->data = new ReviewReportProfile_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->reportProfileRepo = new ReportProfileRepo();

        if(!$this->data()->executor)
            throw new \InvalidArgumentException('Invalid executor');
        if(!$this->data()->reviewedByUserId)
            throw new \InvalidArgumentException('Invalid reviewer');
        if(!$this->data()->report)
            throw new \InvalidArgumentException('Invalid report');

        $this->data()->report->setReviewedByUser((new UserRepo())->getById($this->data()->reviewedByUserId));
        $this->data()->report->setReviewedTime(date('Y-m-d H:i:s'));
        $this->data()->report->setReview($this->data()->review);

        $this->reportProfileRepo->save($this->data()->report);

        $this->report = $this->reportProfileRepo->getById($this->data()->report->getId());
    }

    /**
     * @return ReviewReportProfile_Data
     */
    public function data()
    {
        return $this->data;
    }

    public function getReport()
    {
        return $this->report;
    }
}

class ReviewReportProfile_Data
{
    /** @var User */
    public $executor;

    /** @var ReportProfile */
    public $report;

    /** @var int */
    public $reviewedByUserId;

    /** @var string */
    public $review;
}