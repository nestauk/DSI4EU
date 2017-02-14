<?php

namespace DSI\Controller;

use DSI\Entity\Project;
use DSI\Entity\ProjectMember;
use DSI\Entity\ProjectMemberInvitation;
use DSI\Entity\User;
use DSI\Repository\ProjectMemberInvitationRepository;
use DSI\Repository\ProjectMemberRepository;
use DSI\Repository\ProjectRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\AddMemberInvitationToProject;
use DSI\UseCase\RemoveMemberFromProject;
use DSI\UseCase\RemoveMemberInvitationToProject;
use DSI\UseCase\SearchUser;
use DSI\UseCase\SetAdminStatusToProjectMember;

class ProjectEditMembersController
{
    /** @var  int */
    public $projectID;

    /** @var String */
    public $format = 'html';

    /** @var User */
    private $loggedInUser;

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());
        $this->loggedInUser = $loggedInUser = $authUser->getUser();

        $projectRepository = new ProjectRepository();
        $project = $projectRepository->getById($this->projectID);
        $owner = $project->getOwner();

        $members = (new ProjectMemberRepository())->getByProject($project);
        $isOwner = $this->isOwner($project, $loggedInUser);
        $isAdmin = $this->isAdmin($members, $loggedInUser);

        if (!$isOwner AND !$isAdmin AND !$loggedInUser->isSysAdmin())
            go_to($urlHandler->home());

        try {
            if (isset($_POST['searchExistingUser']))
                return $this->searchExistingUser($_POST['searchExistingUser']);

            if (isset($_POST['addExistingUser']))
                return $this->addExistingUser($project, $_POST['addExistingUser']);

            if (isset($_POST['cancelUserInvitation']))
                return $this->cancelUserInvitation($project, $_POST['cancelUserInvitation']);

            if (isset($_POST['removeMember']))
                return $this->removeMember($project, $_POST['removeMember']);

            if (isset($_POST['makeAdmin']))
                return $this->makeAdmin($project, $_POST['makeAdmin']);

            if (isset($_POST['removeAdmin']))
                return $this->removeAdmin($project, $_POST['removeAdmin']);

        } catch (ErrorHandler $e) {
            echo json_encode([
                'code' => 'error',
                'errors' => $e->getErrors(),
            ]);
            return true;
        }

        if ($this->format == 'json') {
            $invitedMembers = (new ProjectMemberInvitationRepository())->getByProject($project);

            echo json_encode([
                'members' => array_map(function (ProjectMember $member) use ($project) {
                    $user = $member->getMember();
                    return [
                        'id' => $user->getId(),
                        'name' => $user->getFullName(),
                        'jobTitle' => $user->getJobTitle(),
                        'profilePic' => $user->getProfilePic(),
                        'isAdmin' => $member->isAdmin(),
                        'isOwner' => $member->getMemberID() == $project->getOwnerID(),
                    ];
                }, $members),
                'invitedMembers' => array_map(function (ProjectMemberInvitation $invitedMember) use ($project) {
                    $user = $invitedMember->getMember();
                    return [
                        'id' => $user->getId(),
                        'name' => $user->getFullName(),
                        'jobTitle' => $user->getJobTitle(),
                        'profilePic' => $user->getProfilePic(),
                    ];
                }, $invitedMembers)
            ]);
        } else {
            $pageTitle = $project->getName();
            require __DIR__ . '/../../../www/views/project-edit-members.php';
        }

        return true;
    }

    /**
     * @param Project $project
     * @param User $user
     * @return bool
     */
    private function isOwner(Project $project, User $user): bool
    {
        return $project->getOwner()->getId() == $user->getId();
    }

    /**
     * @param ProjectMember[] $members
     * @param User $user
     * @return bool
     */
    private function isAdmin($members, User $user): bool
    {
        foreach ($members AS $member) {
            if (
                ($member->getMemberID() == $user->getId())
                AND
                ($member->isAdmin())
            )
                return true;
        }

        return false;
    }

    private function searchExistingUser($term)
    {
        $search = new SearchUser();
        $search->setTerm($term);
        $search->exec();

        echo json_encode([
            'code' => 'ok',
            'users' => array_map(function (User $user) {
                return [
                    'id' => $user->getId(),
                    'name' => $user->getFullName(),
                    'jobTitle' => $user->getJobTitle(),
                ];
            }, $search->getUsers()),
        ]);

        return true;
    }

    private function addExistingUser(Project $project, $userID)
    {
        $exec = new AddMemberInvitationToProject();
        $exec->setProject($project);
        $exec->setUserID($userID);
        $exec->exec();

        echo json_encode([
            'code' => 'ok',
        ]);

        return true;
    }

    private function cancelUserInvitation(Project $project, $userID)
    {
        $exec = new RemoveMemberInvitationToProject();
        $exec->setProjectID($project->getId());
        $exec->setUserID($userID);
        $exec->exec();

        echo json_encode([
            'code' => 'ok',
        ]);

        return true;
    }

    private function removeMember(Project $project, $userID)
    {
        $exec = new RemoveMemberFromProject();
        $exec->setProject($project);
        $exec->setUserId($userID);
        $exec->exec();

        echo json_encode([
            'code' => 'ok',
        ]);

        return true;
    }

    private function makeAdmin(Project $project, $userID)
    {
        $exec = new SetAdminStatusToProjectMember();
        $exec->setProject($project);
        $exec->setMemberId($userID);
        $exec->setExecutor($this->loggedInUser);
        $exec->setIsAdmin(true);
        $exec->exec();

        echo json_encode([
            'code' => 'ok',
        ]);

        return true;
    }

    private function removeAdmin(Project $project, $userID)
    {
        $exec = new SetAdminStatusToProjectMember();
        $exec->setProject($project);
        $exec->setMemberId($userID);
        $exec->setExecutor($this->loggedInUser);
        $exec->setIsAdmin(false);
        $exec->exec();

        echo json_encode([
            'code' => 'ok',
        ]);

        return true;
    }
}