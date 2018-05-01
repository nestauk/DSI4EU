<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository\CacheMailRepo;
use \DSI\Entity\CacheMail;

class CacheMailRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var CacheMailRepo */
    private $mailRepository;

    public function setUp()
    {
        $this->mailRepository = new CacheMailRepo();
    }

    public function tearDown()
    {
        $this->mailRepository->clearAll();
    }

    /** @test */
    public function mailCanBeCreated()
    {
        $content = new \DSI\Service\Mailer();
        $content->addAddress('alecs@example.org');

        $mail = new CacheMail();
        $mail->setContent($content);
        $this->mailRepository->insert($mail);

        $this->assertEquals(1, $mail->getId());
        $mail = $this->mailRepository->getById($mail->getId());
        $this->assertEquals($content, $mail->getContent());
    }

    /** @test */
    public function canGetAllMails()
    {
        $content = new \DSI\Service\Mailer();

        $mail = new CacheMail();
        $mail->setContent($content);
        $this->mailRepository->insert($mail);

        $this->assertCount(1, $this->mailRepository->getAll());

        $mail = new CacheMail();
        $mail->setContent($content);
        $this->mailRepository->insert($mail);

        $this->assertCount(2, $this->mailRepository->getAll());
    }

    /** @test */
    public function cannotGetNonexistentMail()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->mailRepository->getById(1);
    }
}