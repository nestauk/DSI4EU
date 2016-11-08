<?php

namespace DSI\UseCase;

use DSI\Entity\ProjectMember;
use DSI\Repository\ProjectMemberRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class AddMemberToProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectMemberRepository */
    private $projectMemberRepo;

    /** @var ProjectRepository */
    private $projectRepository;

    /** @var UserRepository */
    private $userRepository;

    /** @var AddMemberToProject_Data */
    private $data;

    public function __construct()
    {
        $this->data = new AddMemberToProject_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectMemberRepo = new ProjectMemberRepository();
        $this->projectRepository = new ProjectRepository();
        $this->userRepository = new UserRepository();

        if ($this->projectMemberRepo->projectIDHasMemberID($this->data()->projectID, $this->data()->userID)) {
            $this->errorHandler->addTaggedError('member', 'This user is already a member of the project');
            $this->errorHandler->throwIfNotEmpty();
        }

        $projectMember = new ProjectMember();
        $projectMember->setMember($this->userRepository->getById($this->data()->userID));
        $projectMember->setProject($this->projectRepository->getById($this->data()->projectID));
        $this->projectMemberRepo->insert($projectMember);
    }

    /**
     * @return AddMemberToProject_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class AddMemberToProject_Data
{
    /** @var int */
    public $userID;

    /** @var int */
    public $projectID;
}