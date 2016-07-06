<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository\ReportProfileRepository;
use \DSI\Repository\UserRepository;
use \DSI\Entity\User;
use \DSI\Entity\ReportProfile;

class ReportProfileRepositoryTest extends PHPUnit_Framework_TestCase
{
    const COMMENT = 'This should not be here';
    const REVIEWED_TIME = '2016-10-10 10:12:12';
    const REVIEW = 'Report Accepted';

    /** @var ReportProfileRepository */
    private $reportRepo;

    /** @var UserRepository */
    private $userRepo;

    /** @var User */
    private $byUser,
        $reportedUser,
        $reviewedByUser;

    public function setUp()
    {
        $this->reportRepo = new ReportProfileRepository;
        $this->userRepo = new UserRepository();

        $this->byUser = new User();
        $this->userRepo->insert($this->byUser);

        $this->reportedUser = new User();
        $this->userRepo->insert($this->reportedUser);

        $this->reviewedByUser= new User();
        $this->userRepo->insert($this->reviewedByUser);
    }

    public function tearDown()
    {
        $this->reportRepo->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test */
    public function reportCanBeSaved()
    {
        $report = new ReportProfile();
        $report->setByUser($this->byUser);
        $report->setReportedUser($this->reportedUser);
        $report->setComment(self::COMMENT);
        $report->setReviewedByUser($this->reviewedByUser);
        $report->setReviewedTime(self::REVIEWED_TIME);
        $report->setReview(self::REVIEW);

        $this->reportRepo->insert($report);

        $this->assertEquals(1, $report->getId());

        $report = (new ReportProfileRepository())->getById($report->getId());
        $this->assertEquals($this->byUser->getId(), $report->getByUserId());
        $this->assertEquals($this->reportedUser->getId(), $report->getReportedUserId());
        $this->assertEquals(self::COMMENT, $report->getComment());
        $this->assertRegExp('<^\d{4}\-\d{2}\-\d{2}\ \d{2}:\d{2}:\d{2}$>', $report->getTime());
        $this->assertEquals($this->reviewedByUser->getId(), $report->getReviewedByUserId());
        $this->assertEquals(self::REVIEWED_TIME, $report->getReviewedTime());
        $this->assertEquals(self::REVIEW, $report->getReview());
    }

    /** @test */
    public function reportCanBeUpdated()
    {
        $report = new ReportProfile();
        $this->reportRepo->insert($report);

        $report = (new ReportProfileRepository())->getById($report->getId());
        $report->setByUser($this->byUser);
        $report->setReportedUser($this->reportedUser);
        $report->setComment(self::COMMENT);
        $report->setReviewedByUser($this->reviewedByUser);
        $report->setReviewedTime(self::REVIEWED_TIME);
        $report->setReview(self::REVIEW);

        $this->reportRepo->save($report);

        $report = (new ReportProfileRepository())->getById($report->getId());
        $this->assertEquals($this->byUser->getId(), $report->getByUserId());
        $this->assertEquals($this->reportedUser->getId(), $report->getReportedUserId());
        $this->assertEquals(self::COMMENT, $report->getComment());
        $this->assertRegExp('<^\d{4}\-\d{2}\-\d{2}\ \d{2}:\d{2}:\d{2}$>', $report->getTime());
        $this->assertEquals($this->reviewedByUser->getId(), $report->getReviewedByUserId());
        $this->assertEquals(self::REVIEWED_TIME, $report->getReviewedTime());
        $this->assertEquals(self::REVIEW, $report->getReview());
    }

    /** @test */
    public function gettingAnNonExistentReportById_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->reportRepo->getById(1);
    }

    /** @test */
    public function NonexistentReportCannotBeSaved()
    {
        $report = new ReportProfile();
        $report->setId(1);

        $this->setExpectedException(\DSI\NotFound::class);
        $this->reportRepo->save($report);
    }

    /** @test getAll */
    public function getAllReports()
    {
        $report = new ReportProfile();
        $this->reportRepo->insert($report);

        $this->assertCount(1, $this->reportRepo->getAll());

        $report = new ReportProfile();
        $this->reportRepo->insert($report);

        $this->assertCount(2, $this->reportRepo->getAll());
    }
}