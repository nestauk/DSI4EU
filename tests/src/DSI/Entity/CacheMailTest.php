<?php

use DSI\Entity\CacheMail;

require_once __DIR__ . '/../../../config.php';

class CacheMailTest extends \PHPUnit_Framework_TestCase
{
    /** @var CacheMail */
    private $mail;

    public function setUp()
    {
        $this->mail = new CacheMail();
    }

    public function tearDown()
    {
        // (new \DSI\Repository\CacheMailRepository())->clearAll();
    }

    /** @test setId, getId */
    public function settingAnId_returnsTheId()
    {
        $this->assertEquals(0, $this->mail->getId());

        $this->mail->setId(1);
        $this->assertEquals(1, $this->mail->getId());
    }

    /** @test setId */
    public function settingAnInvalidId_throwsException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->mail->setId(0);
    }

    /** @test */
    public function settingContent_returnsContent()
    {
        $this->assertEmpty($this->mail->getContent());

        $content = new \DSI\Service\Mailer();
        $content->addAddress($address = 'alecs@example.org');

        $this->mail->setContent($content);
        $this->assertEquals($content, $this->mail->getContent());
    }
}