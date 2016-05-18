<?php

namespace DSI\Service;

class Mailer extends \PHPMailer
{
    public function send()
    {
        $this->addBCC('alecs@inoveb.co.uk');

        file_put_contents(__DIR__ . '/../../../logs/mail-logs/' . microtime(1) . '.json', json_encode($this));

        return parent::send();
    }
}