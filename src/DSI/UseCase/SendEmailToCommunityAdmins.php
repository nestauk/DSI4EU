<?php

namespace DSI\UseCase;

use DSI\Entity\User;
use DSI\Entity\UserLink;
use DSI\Repository\UserLinkRepo;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;
use DSI\Service\Mailer;

class SendEmailToCommunityAdmins
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var SendEmailToCommunityAdmins_Data */
    private $data;

    public function __construct()
    {
        $this->data = new SendEmailToCommunityAdmins_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();

        $userRepo = new UserRepo();
        $admins = $userRepo->getAllCommunityAdmins();
        foreach($admins AS $admin){
            $mail = clone $this->data()->mail;
            $mail->addAddress($admin->getEmail());
            $mail->send();
        }
    }

    /**
     * @return SendEmailToCommunityAdmins_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class SendEmailToCommunityAdmins_Data
{
    /** @var User */
    public $executor;

    /** @var Mailer */
    public $mail;
}