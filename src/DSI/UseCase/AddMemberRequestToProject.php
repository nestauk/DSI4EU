<?php

namespace DSI\UseCase;

use DSI\Entity\ProjectMemberRequest;
use DSI\Repository\ProjectMemberRepo;
use DSI\Repository\ProjectMemberRequestRepo;
use DSI\Repository\ProjectRepo;
use DSI\Repository\ProjectRepoInAPC;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;

class AddMemberRequestToProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectMemberRequestRepo */
    private $projectMemberRequestRepo;

    /** @var ProjectRepo */
    private $projectRepository;

    /** @var UserRepo */
    private $userRepository;

    /** @var AddMemberRequestToProject_Data */
    private $data;

    public function __construct()
    {
        $this->data = new AddMemberRequestToProject_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectMemberRequestRepo = new ProjectMemberRequestRepo();
        $this->projectRepository = new ProjectRepoInAPC();
        $this->userRepository = new UserRepo();

        $this->checkIfProjectAlreadyHasTheMember();
        $this->checkIfThereIsAlreadyARequestFromTheUser();
        $this->addMemberRequest();
    }

    /**
     * @return AddMemberRequestToProject_Data
     */
    public function data()
    {
        return $this->data;
    }

    private function checkIfProjectAlreadyHasTheMember()
    {
        if ((new ProjectMemberRepo())->projectIDHasMemberID($this->data()->projectID, $this->data()->userID)) {
            $this->errorHandler->addTaggedError('member', __('This user is already a member of the project'));
            $this->errorHandler->throwIfNotEmpty();
        }
    }

    private function checkIfThereIsAlreadyARequestFromTheUser()
    {
        if ($this->projectMemberRequestRepo->projectHasRequestFromMember($this->data()->projectID, $this->data()->userID)) {
            $this->errorHandler->addTaggedError('member', __('This user has already made a request to join the project'));
            $this->errorHandler->throwIfNotEmpty();
        }
    }

    private function addMemberRequest()
    {
        $projectMemberRequest = new ProjectMemberRequest();
        $projectMemberRequest->setMember($this->userRepository->getById($this->data()->userID));
        $projectMemberRequest->setProject($this->projectRepository->getById($this->data()->projectID));
        $this->projectMemberRequestRepo->add($projectMemberRequest);
    }
}

class AddMemberRequestToProject_Data
{
    /** @var int */
    public $userID;

    /** @var int */
    public $projectID;
}