<?php

use DSI\Entity\User;

require_once __DIR__ . '/../../../config.php';

class ReportProfileTest extends \PHPUnit_Framework_TestCase
{
    /** @var \DSI\Entity\ReportProfile */
    private $reportProfile;

    /** @var User */
    private $user;

    public function setUp()
    {
        $this->reportProfile = new \DSI\Entity\ReportProfile();

        $this->user = new User();
        $this->user->setId(1);
    }

    /** @test setId, getId */
    public function settingAnId_returnsTheId()
    {
        $this->reportProfile->setId(10);
        $this->assertEquals(10, $this->reportProfile->getId());
    }

    /** @test setId */
    public function settingAnInvalidId_throwsException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->reportProfile->setId(0);
    }

    /** @test */
    public function settingAByUser_returnsByUser()
    {
        $this->reportProfile->setByUser($this->user);
        $this->assertEquals($this->user->getId(), $this->reportProfile->getByUser()->getId());
        $this->assertEquals($this->user->getId(), $this->reportProfile->getByUserId());
    }

    /** @test */
    public function settingAReportedUser_returnsReportedUser()
    {
        $this->reportProfile->setReportedUser($this->user);
        $this->assertEquals($this->user->getId(), $this->reportProfile->getReportedUser()->getId());
        $this->assertEquals($this->user->getId(), $this->reportProfile->getReportedUserId());
    }

    /** @test */
    public function settingNoComment_returnsEmptyComment()
    {
        $this->assertEquals('', $this->reportProfile->getComment());
    }

    /** @test */
    public function settingComment_returnsComment()
    {
        $comment = 'Alexandru';
        $this->reportProfile->setComment($comment);
        $this->assertEquals($comment, $this->reportProfile->getComment());
    }

    /** @test */
    public function settingNoTime_returnsEmptyTime()
    {
        $this->assertEquals('', $this->reportProfile->getTime());
    }

    /** @test */
    public function settingTime_returnsTime()
    {
        $time = '2016-02-10 10:12:12';
        $this->reportProfile->setTime($time);
        $this->assertEquals($time, $this->reportProfile->getTime());
    }

    /** @test */
    public function settingNoReviewedByUser_returnsEmptyReviewedByUser()
    {
        $this->assertEquals(null, $this->reportProfile->getReviewedByUser());
        $this->assertEquals(0, $this->reportProfile->getReviewedByUserId());
    }

    /** @test */
    public function settingAReviewedByUser_returnsReviewedByUser()
    {
        $this->reportProfile->setReviewedByUser($this->user);
        $this->assertEquals($this->user->getId(), $this->reportProfile->getReviewedByUser()->getId());
        $this->assertEquals($this->user->getId(), $this->reportProfile->getReviewedByUserId());
    }

    /** @test */
    public function settingNoReviewedTime_returnsEmptyReviewedTime()
    {
        $this->assertEquals('', $this->reportProfile->getTime());
    }

    /** @test */
    public function settingReviewedTime_returnsReviewedTime()
    {
        $time = '2016-02-10 10:12:12';
        $this->reportProfile->setReviewedTime($time);
        $this->assertEquals($time, $this->reportProfile->getReviewedTime());
    }

    /** @test */
    public function settingNoReview_returnsEmptyReview()
    {
        $this->assertEquals('', $this->reportProfile->getReview());
    }

    /** @test */
    public function settingReview_returnsReview()
    {
        $review = 'Not suggestive enough';
        $this->reportProfile->setReview($review);
        $this->assertEquals($review, $this->reportProfile->getReview());
    }
}