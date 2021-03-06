<?php

namespace DSI\UseCase;

use DSI\Entity\UserLink;
use DSI\Repository\UserLinkRepo;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;

class AddLinkToUser
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UserLinkRepo */
    private $userLinkRepo;

    /** @var AddLinkToUser_Data */
    private $data;

    public function __construct()
    {
        $this->data = new AddLinkToUser_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->userLinkRepo = new UserLinkRepo();

        $userRepo = new UserRepo();

        if($this->userLinkRepo->userHasLink($this->data()->userID, $this->data()->link)) {
            $this->errorHandler->addTaggedError('skill', __('The user already has this link'));
            $this->errorHandler->throwIfNotEmpty();
        }
            
        $userLink = new UserLink();
        $userLink->setLink($this->data()->link);
        $userLink->setUser( $userRepo->getById($this->data()->userID) );
        $this->userLinkRepo->add($userLink);
    }

    /**
     * @return AddLinkToUser_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class AddLinkToUser_Data
{
    /** @var string */
    public $link;

    /** @var int */
    public $userID;
}