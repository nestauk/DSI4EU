<?php

require_once __DIR__ . '/../../../config.php';

class ReviewReportProfileTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\ReviewReportProfile */
    private $reviewReportProfileCmd;

    /** @var \DSI\Repository\ReportProfileRepo */
    private $reportProfileRepo;

    /** @var \DSI\Entity\ReportProfile */
    private $report;

    /** @var \DSI\Repository\UserRepo */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $executor,
        $byUser,
        $reportedUser;

    public function setUp()
    {
        $this->reviewReportProfileCmd = new \DSI\UseCase\ReviewReportProfile();
        $this->reportProfileRepo = new \DSI\Repository\ReportProfileRepo();
        $this->userRepo = new \DSI\Repository\UserRepo();

        $this->executor = new \DSI\Entity\User();
        $this->userRepo->insert($this->executor);

        $this->byUser = new \DSI\Entity\User();
        $this->userRepo->insert($this->byUser);

        $this->reportedUser = new \DSI\Entity\User();
        $this->userRepo->insert($this->reportedUser);

        $this->report = $this->createReportProfile();
    }

    public function tearDown()
    {
        $this->reportProfileRepo->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test */
    public function cannotReviewWithoutExecutor()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $this->reviewReportProfileCmd->exec();
    }

    /** @test */
    public function cannotReviewWithoutReviewer()
    {
        $this->reviewReportProfileCmd->data()->executor = $this->executor;
        $this->setExpectedException(InvalidArgumentException::class);
        $this->reviewReportProfileCmd->exec();
    }

    /** @test */
    public function cannotReviewWithoutReport()
    {
        $this->reviewReportProfileCmd->data()->executor = $this->executor;
        $this->reviewReportProfileCmd->data()->reviewedByUserId = $this->byUser->getId();
        $this->setExpectedException(InvalidArgumentException::class);
        $this->reviewReportProfileCmd->exec();
    }

    /** @test */
    public function canSuccessfullyReviewReport()
    {
        $review = 'Review';

        $this->reviewReportProfileCmd->data()->executor = $this->executor;
        $this->reviewReportProfileCmd->data()->reviewedByUserId = $this->byUser->getId();
        $this->reviewReportProfileCmd->data()->report = $this->report;
        $this->reviewReportProfileCmd->data()->review = $review;
        $this->reviewReportProfileCmd->exec();

        $report = $this->reviewReportProfileCmd->getReport();
        
        $this->assertEquals($this->byUser->getId(), $report->getReviewedByUserId());
        $this->assertEquals($review, $report->getReview());
    }

    /**
     * @return \DSI\Entity\ReportProfile
     */
    private function createReportProfile()
    {
        $createReportProfileCmd = new \DSI\UseCase\CreateReportProfile();
        $createReportProfileCmd->data()->executor = $this->executor;
        $createReportProfileCmd->data()->byUserId = $this->byUser->getId();
        $createReportProfileCmd->data()->reportedUserId = $this->reportedUser->getId();
        $createReportProfileCmd->exec();
        return $createReportProfileCmd->getReport();
    }
}