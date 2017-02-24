<?php

namespace DSI\Controller;

use DSI\Entity\Project;
use DSI\Entity\ProjectEmailInvitation;
use DSI\Entity\ProjectMember;
use DSI\Entity\ProjectMemberInvitation;
use DSI\Entity\User;
use DSI\Repository\ProjectEmailInvitationRepository;
use DSI\Repository\ProjectMemberInvitationRepository;
use DSI\Repository\ProjectMemberRepository;
use DSI\Repository\ProjectRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\JsModules;
use DSI\Service\URL;
use DSI\UseCase\CancelInvitationEmailToProject;
use DSI\UseCase\InviteEmailToProject;
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

        $members = (new ProjectMemberRepository())->getByProject($project);
        $isOwner = $this->isProjectOwner($project, $loggedInUser);
        $isAdmin = $this->isProjectAdmin($members, $loggedInUser);

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

            if (isset($_POST['inviteEmail']))
                return $this->inviteEmail($project, $_POST['inviteEmail']);

            if (isset($_POST['cancelInvitationForEmail']))
                return $this->cancelInvitationForEmail($project, $_POST['cancelInvitationForEmail']);

        } catch (ErrorHandler $e) {
            echo json_encode([
                'code' => 'error',
                'errors' => $e->getErrors(),
            ]);
            return null;
        }

        if ($this->format == 'json') {
            echo json_encode([
                'members' => $this->projectMembersJson($members),
                'invitedMembers' => $this->projectInvitedMembersJson($project),
                'invitedEmails' => $this->projectInvitedEmailsJson($project),
            ]);
        } else {
            $pageTitle = $project->getName();
            JsModules::setTranslations(true);
            require __DIR__ . '/../../../www/views/project-edit-members.php';
        }

        return null;
    }

    /**
     * @param Project $project
     * @param User $user
     * @return bool
     */
    private function isProjectOwner(Project $project, User $user): bool
    {
        return $project->getOwner()->getId() == $user->getId();
    }

    /**
     * @param ProjectMember[] $members
     * @param User $user
     * @return bool
     */
    private function isProjectAdmin($members, User $user): bool
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

    /**
     * @param String $term
     * @return bool
     */
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

    /**
     * @param Project $project
     * @param int $userID
     * @return bool
     */
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

    /**
     * @param Project $project
     * @param int $userID
     * @return bool
     */
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

    /**
     * @param Project $project
     * @param int $userID
     * @return bool
     */
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

    /**
     * @param Project $project
     * @param int $userID
     * @return bool
     */
    private function makeAdmin(Project $project, int $userID)
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

    /**
     * @param Project $project
     * @param int $userID
     * @return bool
     */
    private function removeAdmin(Project $project, int $userID)
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

    /**
     * @param Project $project
     * @param string $email
     * @return bool
     */
    private function inviteEmail(Project $project, string $email)
    {
        $exec = new InviteEmailToProject();
        $exec->setProject($project);
        $exec->setByUser($this->loggedInUser);
        $exec->setEmail($email);
        $exec->exec();

        echo json_encode([
            'code' => 'ok',
        ]);

        return true;
    }

    /**
     * @param Project $project
     * @param string $email
     * @return bool
     */
    private function cancelInvitationForEmail(Project $project, string $email)
    {
        $exec = new CancelInvitationEmailToProject();
        $exec->setProject($project);
        $exec->setEmail($email);
        $exec->exec();

        echo json_encode([
            'code' => 'ok',
        ]);

        return true;
    }


    /**
     * @param ProjectMember[] $members
     * @return array
     */
    private function projectMembersJson($members)
    {
        return array_map(function (ProjectMember $member) {
            $user = $member->getMember();
            $project = $member->getProject();
            return [
                'id' => $user->getId(),
                'name' => $user->getFullName(),
                'jobTitle' => $user->getJobTitle(),
                'profilePic' => $user->getProfilePic(),
                'isAdmin' => $member->isAdmin(),
                'isOwner' => $member->getMemberID() == $project->getOwnerID(),
            ];
        }, $members);
    }

    /**
     * @param Project $project
     * @return array
     */
    private function projectInvitedMembersJson(Project $project)
    {
        $invitedMembers = (new ProjectMemberInvitationRepository())->getByProject($project);
        return array_map(function (ProjectMemberInvitation $invitedMember) {
            $user = $invitedMember->getMember();
            return [
                'id' => $user->getId(),
                'name' => $user->getFullName(),
                'jobTitle' => $user->getJobTitle(),
                'profilePic' => $user->getProfilePic(),
            ];
        }, $invitedMembers);
    }

    /**
     * @param Project $project
     * @return array
     */
    private function projectInvitedEmailsJson(Project $project)
    {
        $invitedEmails = (new ProjectEmailInvitationRepository())->getByProject($project);
        return array_map(function (ProjectEmailInvitation $invitedEmail) {
            return [
                'email' => $invitedEmail->getEmail(),
            ];
        }, $invitedEmails);
    }
}