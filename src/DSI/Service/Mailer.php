<?php

namespace DSI\Service;

class Mailer extends \PHPMailer
{
    public function wrapMessageInTemplate($data)
    {
        $mailHeader = $data['header'];
        $mailBody = $data['body'];

        ob_start();
        require(__DIR__ . '/../../email-template/default.php');
        $newMailBody = ob_get_clean();

        $return = parent::msgHTML($newMailBody);

        $this->AltBody = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", PHP_EOL, $this->AltBody);
        $this->AltBody = preg_replace("/^[\s\t]+/m", "", $this->AltBody);

        return $return;
    }

    public function send()
    {
        $this->addBCC('alecs@inoveb.co.uk');

        file_put_contents(__DIR__ . '/../../../logs/mail-logs/' . microtime(1) . '.json', json_encode($this));

        return parent::send();
    }
}