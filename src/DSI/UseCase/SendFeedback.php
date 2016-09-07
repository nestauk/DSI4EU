<?php

namespace DSI\UseCase;

use DSI\Service\ErrorHandler;
use DSI\Service\Mailer;

class SendFeedback
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var SendFeedback_Data */
    private $data;

    public function __construct()
    {
        $this->data = new SendFeedback_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();

        $this->checkDataHasBeenSubmitted();
        $this->checkEmailIsValid();
        $this->sendEmail();
    }

    /**
     * @return SendFeedback_Data
     */
    public function data()
    {
        return $this->data;
    }

    private function checkDataHasBeenSubmitted()
    {
        if (!$this->data()->name)
            $this->errorHandler->addTaggedError('name', 'Please type your name');
        if (!$this->data()->email)
            $this->errorHandler->addTaggedError('email', 'Please type your email');
        if (!$this->data()->message)
            $this->errorHandler->addTaggedError('message', 'Please type your message');

        $this->errorHandler->throwIfNotEmpty();
    }

    private function checkEmailIsValid()
    {
        if (!filter_var($this->data()->email, FILTER_VALIDATE_EMAIL)) {
            $this->errorHandler->addTaggedError('email', "Please type a valid email address");
            throw $this->errorHandler;
        }
    }

    private function sendEmail()
    {
        if ($this->data()->sendEmail) {
            $message = "<div>";
            $message .= "<b>Name</b>: " . $this->data()->name . '<br />' . PHP_EOL;
            $message .= "<b>Email</b>: " . $this->data()->email . '<br />' . PHP_EOL;
            $message .= "<b>Message</b>: " . $this->data()->message . '<br />' . PHP_EOL;
            $message .= "</div>";
            $email = new Mailer();
            $email->From = 'noreply@digitalsocial.eu';
            $email->FromName = 'No Reply';
            $email->addAddress('alexandru.pandele@nesta.org.uk');
            $email->addAddress('daniel.pettifer@nesta.org.uk');
            $email->addAddress('gail.dawes@nesta.org.uk');
            $email->Subject = 'Digital Social Innovation :: Feedback Form';
            $email->wrapMessageInTemplate([
                'header' => 'Feedback Form',
                'body' => $message,
            ]);
            $email->isHTML(true);
            $email->send();
        }
    }
}

class SendFeedback_Data
{
    /** @var string */
    public $name,
        $email,
        $message;

    /** @var bool */
    public $sendEmail = false;
}