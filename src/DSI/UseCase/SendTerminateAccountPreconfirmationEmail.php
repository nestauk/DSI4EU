<?php

namespace DSI\UseCase;

use DSI\Entity\User;
use DSI\Service\ErrorHandler;
use DSI\Service\Mailer;
use Services\URL;

class SendTerminateAccountPreconfirmationEmail
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var boolean */
    private $sendEmail;

    /** @var User */
    private $user;

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();

        $this->sendEmail();
    }

    private function sendEmail()
    {
        $urlHandler = new URL();

        $createToken = new TerminateAccount\CreateToken();
        $createToken->setUser($this->user);
        $createToken->exec();
        $token = $createToken->getToken();

        $url = $urlHandler->fullUrl(
            $urlHandler->terminateAccount(
                $token->getToken()
            )
        );

        if ($this->sendEmail) {
            $message = "You have requested to terminate your DSI account.<br />";
            $message .= "Please click the link below to confirm your request<br /><br />";
            $message .= "<a href='{$url}'>Click here</a> to terminate your account";
            $email = new Mailer();
            $email->From = 'noreply@digitalsocial.eu';
            $email->FromName = 'Digital Social';
            $email->addAddress($this->user->getEmail());
            $email->Subject = 'Digital Social Innovation :: Terminate Account';
            $email->wrapMessageInTemplate([
                'header' => 'Terminate Account',
                'body' => $message,
            ]);
            $email->isHTML(true);
            $email->send();
        }
    }

    /**
     * @param bool $sendEmail
     */
    public function setSendEmail(bool $sendEmail)
    {
        $this->sendEmail = $sendEmail;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }
}