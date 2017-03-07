<?php

namespace DSI\UseCase;

use DSI\Entity\ProjectMember;
use DSI\Entity\ProjectMemberRequest;
use DSI\Entity\User;
use DSI\Repository\ProjectMemberRepository;
use DSI\Repository\ProjectMemberRequestRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\ProjectRepositoryInAPC;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class ApproveMemberRequestToProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectMemberRequestRepository */
    private $projectMemberRequestRepo;

    /** @var ProjectMemberRepository */
    private $projectMemberRepo;

    /** @var ProjectRepository */
    private $projectRepository;

    /** @var UserRepository */
    private $userRepository;

    /** @var ApproveMemberRequestToProject_Data */
    private $data;

    public function __construct()
    {
        $this->data = new ApproveMemberRequestToProject_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectMemberRequestRepo = new ProjectMemberRequestRepository();
        $this->projectMemberRepo = new ProjectMemberRepository();
        $this->projectRepository = new ProjectRepositoryInAPC();
        $this->userRepository = new UserRepository();

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

        $projectMember = new ProjectMember();
        $projectMember->setMember($member);
        $projectMember->setProject($project);
        $this->projectMemberRepo->insert($projectMember);
    }

    /**
     * @return ApproveMemberRequestToProject_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class ApproveMemberRequestToProject_Data
{
    /** @var User */
    public $executor;

    /** @var int */
    public $userID;

    /** @var int */
    public $projectID;
}