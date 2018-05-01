<?php

require_once __DIR__ . '/../../../config.php';

class SendFeedbackTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\SendFeedback */
    private $sendFeedbackCmd;

    public function setUp()
    {
        $this->sendFeedbackCmd = new \DSI\UseCase\SendFeedback();
    }

    public function tearDown()
    {
    }

    /** @test */
    public function cannotSendEmptyData()
    {
        $e = null;
        try {
            $this->sendFeedbackCmd->exec();
        } catch (\DSI\Service\ErrorHandler $e){
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('name'));
        $this->assertNotEmpty($e->getTaggedError('email'));
        $this->assertNotEmpty($e->getTaggedError('message'));
    }

    /** @test */
    public function cannotSendInvalidEmail()
    {
        $e = null;
        try {
            $this->sendFeedbackCmd->data()->name = 'name';
            $this->sendFeedbackCmd->data()->email = 'invalid-email';
            $this->sendFeedbackCmd->data()->message = 'message';
            $this->sendFeedbackCmd->exec();
        } catch (\DSI\Service\ErrorHandler $e){
        }

        $this->assertNotNull($e);
        $this->assertEmpty($e->getTaggedError('name'));
        $this->assertNotEmpty($e->getTaggedError('email'));
        $this->assertEmpty($e->getTaggedError('message'));
    }

    /** @test */
    public function sendingAllCorrectDataWorks()
    {
        $e = null;
        try {
            $this->sendFeedbackCmd->data()->name = 'name';
            $this->sendFeedbackCmd->data()->email = 'email@example.org';
            $this->sendFeedbackCmd->data()->message = 'message';
            $this->sendFeedbackCmd->exec();
        } catch (\DSI\Service\ErrorHandler $e){
        }

        $this->assertNull($e);
    }
}