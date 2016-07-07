<?php

require_once __DIR__ . '/../../../config.php';

class CreateReportProfileTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\CreateReportProfile */
    private $createReportProfileCmd;

    /** @var \DSI\Repository\ReportProfileRepository */
    private $reportProfileRepo;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $executor,
        $byUser,
        $reportedUser;

    public function setUp()
    {
        $this->createReportProfileCmd = new \DSI\UseCase\CreateReportProfile();
        $this->reportProfileRepo = new \DSI\Repository\ReportProfileRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();

        $this->executor = new \DSI\Entity\User();
        $this->userRepo->insert($this->executor);

        $this->byUser = new \DSI\Entity\User();
        $this->userRepo->insert($this->byUser);

        $this->reportedUser = new \DSI\Entity\User();
        $this->userRepo->insert($this->reportedUser);
    }

    public function tearDown()
    {
        $this->reportProfileRepo->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test */
    public function cannotCreateWithoutExecutor()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $this->createReportProfileCmd->exec();
    }

    /** @test */
    public function cannotCreateWithoutByUserID()
    {
        $this->createReportProfileCmd->data()->executor = $this->executor;
        $this->setExpectedException(InvalidArgumentException::class);
        $this->createReportProfileCmd->exec();
    }

    /** @test */
    public function cannotCreateWithoutReportedUser()
    {
        $this->createReportProfileCmd->data()->executor = $this->executor;
        $this->createReportProfileCmd->data()->byUserId = $this->byUser->getId();;
        $this->setExpectedException(InvalidArgumentException::class);
        $this->createReportProfileCmd->exec();
    }

    /** @test */
    public function canSuccessfullyReportProfile()
    {
        $this->createReportProfileCmd->data()->executor = $this->executor;
        $this->createReportProfileCmd->data()->byUserId = $this->byUser->getId();;
        $this->createReportProfileCmd->data()->reportedUserId = $this->reportedUser->getId();;
        $this->createReportProfileCmd->exec();

        $this->assertCount(1, $this->reportProfileRepo->getAll());
    }

    /** @test */
    public function canSuccessfullyReportProfileWithComment()
    {
        $comment = 'Comment';

        $this->createReportProfileCmd->data()->executor = $this->executor;
        $this->createReportProfileCmd->data()->byUserId = $this->byUser->getId();
        $this->createReportProfileCmd->data()->reportedUserId = $this->reportedUser->getId();
        $this->createReportProfileCmd->data()->comment = $comment;
        $this->createReportProfileCmd->exec();

        $report = $this->reportProfileRepo->getAll()[0];
        $this->assertEquals($comment, $report->getComment());
    }
}