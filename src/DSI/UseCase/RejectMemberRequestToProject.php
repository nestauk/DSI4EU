<?php

namespace DSI\UseCase;

use DSI\Entity\ProjectMember;
use DSI\Entity\ProjectMemberRequest;
use DSI\Entity\User;
use DSI\Repository\ProjectMemberRepo;
use DSI\Repository\ProjectMemberRequestRepo;
use DSI\Repository\ProjectRepo;
use DSI\Repository\ProjectRepoInAPC;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;

class RejectMemberRequestToProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectMemberRequestRepo */
    private $projectMemberRequestRepo;

    /** @var ProjectMemberRepo */
    private $projectMemberRepo;

    /** @var ProjectRepo */
    private $projectRepository;

    /** @var UserRepo */
    private $userRepository;

    /** @var RejectMemberRequestToProject_Data */
    private $data;

    public function __construct()
    {
        $this->data = new RejectMemberRequestToProject_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectMemberRequestRepo = new ProjectMemberRequestRepo();
        $this->projectMemberRepo = new ProjectMemberRepo();
        $this->projectRepository = new ProjectRepoInAPC();
        $this->userRepository = new UserRepo();

        if (!$this->projectMemberRequestRepo->projectHasRequestFromMember($this->data()->projectID, $this->data()->userID)) {
            $this->errorHandler->addTaggedError('member', 'This user has not made a request to join the project');
            $this->errorHandler->throwIfNotEmpty();
        }

        $member = $this->userRepository->getById($this->data()->userID);
        $project = $this->projectRepository->getById($this->data()->projectID);

        $projectMemberRequest = new ProjectMemberRequest();
        $projectMemberRequest->setMember($member);
        $projectMemberRequest->setProject($project);
        $this->projectMemberRequestRepo->remove($projectMemberRequest);
    }

    /**
     * @return RejectMemberRequestToProject_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class RejectMemberRequestToProject_Data
{
    /** @var User */
    public $executor;

    /** @var int */
    public $userID;

    /** @var int */
    public $projectID;
}