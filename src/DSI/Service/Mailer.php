<?php

namespace DSI\Service;

class Mailer extends \PHPMailer
{
    public function msgHTML($mailBody, $basedir = '', $advanced = false)
    {
        ob_start();
        require(__DIR__ . '/../../email-template/default.php');
        $newMailBody = ob_get_clean();

        return parent::msgHTML($newMailBody, $basedir, $advanced);
    }

    public function send()
    {
        $this->addBCC('alecs@inoveb.co.uk');

        file_put_contents(__DIR__ . '/../../../logs/mail-logs/' . microtime(1) . '.json', json_encode($this));

        return parent::send();
    }
}