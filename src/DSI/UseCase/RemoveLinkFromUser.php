<?php

namespace DSI\UseCase;

use DSI\Entity\UserLink;
use DSI\Repository\UserLinkRepository;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class RemoveLinkFromUser
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UserLinkRepository */
    private $userLinkRepo;

    /** @var RemoveLinkFromUser_Data */
    private $data;

    public function __construct()
    {
        $this->data = new RemoveLinkFromUser_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->userLinkRepo = new UserLinkRepository();

        $userRepo = new UserRepository();

        if (!$this->userLinkRepo->userHasLink($this->data()->userID, $this->data()->link)) {
            $this->errorHandler->addTaggedError('link', __('The user does not have this link'));
            $this->errorHandler->throwIfNotEmpty();
        }

        $userLink = new UserLink();
        $userLink->setLink($this->data()->link);
        $userLink->setUser($userRepo->getById($this->data()->userID));
        $this->userLinkRepo->remove($userLink);
    }

    /**
     * @return RemoveLinkFromUser_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class RemoveLinkFromUser_Data
{
    /** @var string */
    public $link;

    /** @var int */
    public $userID;
}