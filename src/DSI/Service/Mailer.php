<?php

namespace DSI\Service;

use DSI\UseCase\CacheUnsentEmail;

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
        if(count($this->getToAddresses()) == 0){
            error_log('Empty Recipients');
            return false;
        }

        file_put_contents(__DIR__ . '/../../../logs/mail-logs/' . microtime(1) . '.json', json_encode($this));
        file_put_contents(__DIR__ . '/../../../logs/mail-logs/' . microtime(1) . '.html', $this->Body);

        $returnCode = parent::send();
        if (!$returnCode) {
            error_log('Could not send email to: ');
            error_log(var_export($this->getToAddresses(), true));
            error_log('Error: ' . $this->ErrorInfo);

            $cacheEmail = new CacheUnsentEmail();
            $cacheEmail->data()->content = $this;
            $cacheEmail->exec();

            return false;
        } else {
            file_put_contents(__DIR__ . '/../../../logs/mail-logs/' . microtime(1) . '.json', json_encode($this));

            return true;
        }
    }
}