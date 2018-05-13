<?php

namespace DSI\UseCase;

use DSI\NotEnoughData;
use DSI\Service\ErrorHandler;
use DSI\Service\Mailer;

class SendWelcomeEmailAfterRegistration
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var SendWelcomeEmailAfterRegistration_Data */
    private $data;

    public function __construct()
    {
        $this->data = new SendWelcomeEmailAfterRegistration_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();

        $this->checkDataHasBeenSubmitted();
        $this->checkValidData();
        $this->sendEmail();
    }

    /**
     * @return SendWelcomeEmailAfterRegistration_Data
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
            $this->errorHandler->addTaggedError('email', __('Please type a valid email address'));

        $this->errorHandler->throwIfNotEmpty();
    }

    private function sendEmail()
    {
        ob_start();
        include __DIR__ . '/../../../resources/views/emails/welcome.php';
        $message = ob_get_clean();
        $email = new Mailer();
        $email->From = 'noreply@digitalsocial.eu';
        $email->FromName = 'Digital Social Innovation';
        $email->addAddress($this->data()->emailAddress);
        $email->Subject = 'Welcome to Digital Social Innovation';
        $email->wrapMessageInTemplate([
            'header' => 'Welcome to Digital Social Innovation',
            'body' => $message
        ]);
        $email->isHTML(true);
        $email->send();
    }
}

class SendWelcomeEmailAfterRegistration_Data
{
    /** @var string */
    public $emailAddress;
}