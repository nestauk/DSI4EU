<?php

namespace DSI\UseCase;

use DSI\Entity\ProjectMember;
use DSI\Repository\ProjectMemberRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class RemoveMemberFromProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectMemberRepository */
    private $projectMemberRepo;

    /** @var RemoveMemberFromProject_Data */
    private $data;

    public function __construct()
    {
        $this->data = new RemoveMemberFromProject_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectMemberRepo = new ProjectMemberRepository();

        $projectRepo = new ProjectRepository();
        $userRepo = new UserRepository();

        if (!$this->projectMemberRepo->projectIDHasMemberID($this->data()->projectID, $this->data()->userID)) {
            $this->errorHandler->addTaggedError('member', 'User is not a member of the project');
            $this->errorHandler->throwIfNotEmpty();
        }

        $projectMember = new ProjectMember();
        $projectMember->setMember($userRepo->getById($this->data()->userID));
        $projectMember->setProject($projectRepo->getById($this->data()->projectID));
        $this->projectMemberRepo->remove($projectMember);
    }

    /**
     * @return RemoveMemberFromProject_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class RemoveMemberFromProject_Data
{
    /** @var int */
    public $userID;

    /** @var int */
    public $projectID;
}