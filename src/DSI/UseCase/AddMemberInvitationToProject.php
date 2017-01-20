<?php

namespace DSI\UseCase;

use DSI\Entity\ProjectMemberInvitation;
use DSI\Repository\ProjectMemberRepository;
use DSI\Repository\ProjectMemberInvitationRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class AddMemberInvitationToProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectMemberInvitationRepository*/
    private $projectMemberInvitationRepo;

    /** @var ProjectRepository */
    private $projectRepository;

    /** @var UserRepository */
    private $userRepository;

    /** @var AddMemberInvitationToProject_Data */
    private $data;

    public function __construct()
    {
        $this->data = new AddMemberInvitationToProject_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectMemberInvitationRepo = new ProjectMemberInvitationRepository();
        $this->projectRepository = new ProjectRepository();
        $this->userRepository = new UserRepository();

        $this->checkIfProjectAlreadyHasTheMember();
        $this->checkIfUserHasAlreadyBeenInvited();
        $this->addMemberInvitation();
    }

    /**
     * @return AddMemberInvitationToProject_Data
     */
    public function data()
    {
        return $this->data;
    }

    private function checkIfProjectAlreadyHasTheMember()
    {
        if ((new ProjectMemberRepository())->projectIDHasMemberID($this->data()->projectID, $this->data()->userID)) {
            $this->errorHandler->addTaggedError('member', __('This user is already a member of the project'));
            $this->errorHandler->throwIfNotEmpty();
        }
    }

    private function checkIfUserHasAlreadyBeenInvited()
    {
        if ($this->projectMemberInvitationRepo->memberHasInvitationToProject($this->data()->userID, $this->data()->projectID)) {
            $this->errorHandler->addTaggedError('member', __('This user has already been invited to join the project'));
            $this->errorHandler->throwIfNotEmpty();
        }
    }

    private function addMemberInvitation()
    {
        $projectMemberInvitation = new ProjectMemberInvitation();
        $projectMemberInvitation->setMember($this->userRepository->getById($this->data()->userID));
        $projectMemberInvitation->setProject($this->projectRepository->getById($this->data()->projectID));
        $this->projectMemberInvitationRepo->add($projectMemberInvitation);
    }
}

class AddMemberInvitationToProject_Data
{
    /** @var int */
    public $userID;

    /** @var int */
    public $projectID;
}