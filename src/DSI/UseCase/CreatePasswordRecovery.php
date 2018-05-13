<?php

namespace DSI\UseCase;

use DSI\Entity\PasswordRecovery;
use DSI\NotEnoughData;
use DSI\Repository\PasswordRecoveryRepo;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;
use DSI\Service\Mailer;

class CreatePasswordRecovery
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var PasswordRecoveryRepo */
    private $passwordRecoveryRepo;

    /** @var PasswordRecovery */
    private $passwordRecovery;

    /** @var CreatePasswordRecovery_Data */
    private $data;

    public function __construct()
    {
        $this->data = new CreatePasswordRecovery_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->passwordRecoveryRepo = new PasswordRecoveryRepo();

        if (!isset($this->data()->email))
            throw new NotEnoughData('email');

        $userRepository = new UserRepo();

        if (!$userRepository->emailAddressExists($this->data()->email)) {
            $this->errorHandler->addTaggedError('email', __('The email address is not registered'));
            throw $this->errorHandler;
        }

        $user = $userRepository->getByEmail($this->data()->email);

        $passwordRecovery = new PasswordRecovery();
        $passwordRecovery->setCode($this->generateRandomCode());
        $passwordRecovery->setUser($user);
        $this->passwordRecoveryRepo->insert($passwordRecovery);

        $this->passwordRecovery = $passwordRecovery;

        if ($this->data()->sendEmail) {
            ob_start();
            require(__DIR__ . '/../../../resources/views/emails/resetPassword.php');
            $message = "<div>";
            $message .= ob_get_clean();
            $message .= "</div>";

            $email = new Mailer();
            $email->From = 'noreply@digitalsocial.eu';
            $email->FromName = 'Digital Social';
            $email->addAddress($passwordRecovery->getUser()->getEmail());
            $email->Subject = 'Digital Social Innovation :: Password Recovery';
            $email->wrapMessageInTemplate([
                'header' => 'Password Recovery',
                'body' => $message
            ]);
            $email->isHTML(true);
            $email->send();
        }
    }

    /**
     * @return CreatePasswordRecovery_Data
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return PasswordRecovery
     */
    public function getPasswordRecovery()
    {
        return $this->passwordRecovery;
    }

    /**
     * @return string
     */
    private function generateRandomCode()
    {
        $length = 5;

        $randomString = '';
        for ($i = 0; $i < $length; $i++)
            $randomString .= random_int(0, 9);

        return $randomString;
    }
}

class CreatePasswordRecovery_Data
{
    /** @var String */
    public $email;

    /** @var bool */
    public $sendEmail = false;
}