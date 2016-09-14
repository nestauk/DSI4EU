<?php

namespace DSI\UseCase;

use DSI\Entity\CacheMail;
use DSI\Repository\CacheMailRepository;
use DSI\Service\ErrorHandler;
use DSI\Service\Mailer;

class CacheUnsentEmail
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var CacheUnsentEmail_Data */
    private $data;

    public function __construct()
    {
        $this->data = new CacheUnsentEmail_Data();
        $this->errorHandler = new ErrorHandler();
    }

    public function exec()
    {
        $this->assertContentHasBeenSent();
        $this->saveMail();
    }

    /**
     * @return CacheUnsentEmail_Data
     */
    public function data()
    {
        return $this->data;
    }

    private function assertContentHasBeenSent()
    {
        if (!$this->data()->content) {
            $this->errorHandler->addTaggedError('content', 'Please send the mailer content');
            throw $this->errorHandler;
        }
    }

    private function saveMail()
    {
        $mail = new CacheMail();
        $mail->setContent($this->data()->content);
        (new CacheMailRepository())->insert($mail);
    }
}

class CacheUnsentEmail_Data
{
    /** @var Mailer */
    public $content;
}