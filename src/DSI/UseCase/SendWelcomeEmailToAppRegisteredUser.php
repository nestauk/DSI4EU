<?php

namespace DSI\UseCase;

use DSI\NotEnoughData;
use DSI\Service\Mailer;
use DSI\Service\ErrorHandler;

class SendWelcomeEmailToAppRegisteredUser
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var SendWelcomeEmailToAppRegisteredUser_Data */
    private $data;

    public function __construct()
    {
        $this->data = new SendWelcomeEmailToAppRegisteredUser_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();

        $this->checkDataHasBeenSubmitted();
        $this->checkValidData();
        $this->sendEmail();
    }

    /**
     * @return SendWelcomeEmailToAppRegisteredUser_Data
     */
    public function data()
    {
        return $this->data;
    }

    private function checkDataHasBeenSubmitted()
    {
        if (!isset($this->data()->emailAddress))
            throw new NotEnoughData('email');
    }

    private function checkValidData()
    {
        if (!filter_var($this->data()->emailAddress, FILTER_VALIDATE_EMAIL))
            $this->errorHandler->addTaggedError('email', 'Please type a valid email address');

        $this->errorHandler->throwIfNotEmpty();
    }

    private function sendEmail()
    {
        $code = $this->data()->code;
        ob_start();
        require(__DIR__ . '/../../email-template/welcomeFromApp.php');

        $message = "<div>";
        $message .= ob_get_clean();
        $message .= "</div>";
        $email = new Mailer();
        $email->From = 'noreply@digitalsocial.eu';
        $email->FromName = 'Digital Social';
        $email->addAddress($this->data()->emailAddress);
        $email->Subject = 'Digital Social Innovation :: Welcome';
        $email->wrapMessageInTemplate([
            'header' => 'Welcome to Digital Social',
            'body' => $message
        ]);
        $email->isHTML(true);
        $email->send();
    }
}

class SendWelcomeEmailToAppRegisteredUser_Data
{
    /** @var string */
    public $emailAddress,
        $code;
}