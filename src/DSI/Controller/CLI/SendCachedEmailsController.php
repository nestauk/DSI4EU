<?php

namespace DSI\Controller\CLI;

use DSI\UseCase\SendCachedEmails;

class SendCachedEmailsController
{
    public function exec()
    {
        $useCase = new SendCachedEmails();
        $useCase->exec();
    }
}