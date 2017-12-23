<?php

namespace DSI\UseCase\TerminateAccount;

use DSI\Entity\TerminateAccountToken;
use DSI\Entity\User;
use DSI\Repository\OrganisationFollowRepo;
use DSI\Repository\OrganisationMemberInvitationRepo;
use DSI\Repository\OrganisationMemberRepo;
use DSI\Repository\OrganisationMemberRequestRepo;
use DSI\Repository\OrganisationRepo;
use DSI\Repository\ProjectFollowRepo;
use DSI\Repository\ProjectMemberInvitationRepo;
use DSI\Repository\ProjectMemberRepo;
use DSI\Repository\ProjectMemberRequestRepo;
use DSI\Repository\ProjectPostCommentReplyRepo;
use DSI\Repository\ProjectPostCommentRepo;
use DSI\Repository\ProjectPostRepo;
use DSI\Repository\ProjectRepo;
use DSI\Repository\ProjectRepoInAPC;
use DSI\Repository\TerminateAccountTokenRepo;
use DSI\Repository\UserLinkRepo;
use DSI\Repository\UserRepo;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;

class TerminateAccount
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var User */
    private $user;

    /** @var TerminateAccountToken */
    private $token;

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();

        $this->updateProjects();
        $this->updateOrganisations();
        $this->removeUserLinks();
        $this->removeToken();
        $this->logoutUser();
        $this->removeUser();
    }

    /**
     * @param TerminateAccountToken $token
     */
    public function setToken(TerminateAccountToken $token)
    {
        $this->token = $token;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    private function updateOrganisations()
    {
        $sysAdmin = $this->getSysAdmin();

        $organisationRepo = new OrganisationRepo();
        $organisations = $organisationRepo->getByUser($this->user);
        foreach ($organisations AS $organisation) {
            $organisation->setIsPublished(false);
            $organisation->setOwner($sysAdmin);
            $organisationRepo->save($organisation);
        }

        $organisationFollowRepo = new OrganisationFollowRepo();
        $organisationFollows = $organisationFollowRepo->getByUser($this->user);
        foreach ($organisationFollows AS $organisationFollow)
            $organisationFollowRepo->remove($organisationFollow);

        $organisationMemberInvitationRepo = new OrganisationMemberInvitationRepo();
        $organisationMemberInvitations = $organisationMemberInvitationRepo->getByMemberID($this->user->getId());
        foreach ($organisationMemberInvitations AS $organisationMemberInvitation)
            $organisationMemberInvitationRepo->remove($organisationMemberInvitation);

        $organisationMemberRequestRepo = new OrganisationMemberRequestRepo();
        $organisationMemberRequests = $organisationMemberRequestRepo->getByMemberID($this->user->getId());
        foreach ($organisationMemberRequests AS $organisationMemberRequest)
            $organisationMemberRequestRepo->remove($organisationMemberRequest);

        $organisationMemberRepo = new OrganisationMemberRepo();
        $organisationMembers = $organisationMemberRepo->getByMemberID($this->user->getId());
        foreach ($organisationMembers AS $organisationMember)
            $organisationMemberRepo->remove($organisationMember);
    }

    private function updateProjects()
    {
        $sysAdmin = $this->getSysAdmin();

        $projectRepo = new ProjectRepoInAPC();
        $projects = $projectRepo->getByUser($this->user);
        foreach ($projects AS $project) {
            $project->setIsPublished(false);
            $project->setOwner($sysAdmin);
            $projectRepo->save($project);
        }

        $projectFollowRepo = new ProjectFollowRepo();
        $projectFollows = $projectFollowRepo->getByUser($this->user);
        foreach ($projectFollows as $projectFollow)
            $projectFollowRepo->remove($projectFollow);

        $projectMemberInvitationRepo = new ProjectMemberInvitationRepo();
        $projectMemberInvitations = $projectMemberInvitationRepo->getByMemberID($this->user->getId());
        foreach ($projectMemberInvitations as $projectMemberInvitation)
            $projectMemberInvitationRepo->remove($projectMemberInvitation);

        $projectMemberRequestRepo = new ProjectMemberRequestRepo();
        $projectMemberRequests = $projectMemberRequestRepo->getByMemberID($this->user->getId());
        foreach ($projectMemberRequests as $projectMemberRequest)
            $projectMemberRequestRepo->remove($projectMemberRequest);

        $projectMemberRepo = new ProjectMemberRepo();
        $projectMembers = $projectMemberRepo->getByMemberID($this->user->getId());
        foreach ($projectMembers as $projectMember)
            $projectMemberRepo->remove($projectMember);

        $projectPostCommentReplyRepo = new ProjectPostCommentReplyRepo();
        $replies = $projectPostCommentReplyRepo->getByUser($this->user);
        foreach ($replies as $reply)
            $projectPostCommentReplyRepo->remove($reply);

        $projectPostCommentRepo = new ProjectPostCommentRepo();
        $projectPostComments = $projectPostCommentRepo->getByUser($this->user);
        foreach ($projectPostComments AS $projectPostComment)
            $projectPostCommentRepo->save($projectPostComment);

        $projectPostRepo = new ProjectPostRepo();
        $projectPosts = $projectPostRepo->getByUser($this->user);
        foreach ($projectPosts AS $projectPost)
            $projectPostRepo->remove($projectPost);
    }

    /**
     * @return User
     */
    private function getSysAdmin(): User
    {
        return (new UserRepo())->getById(1);
    }

    private function removeUserLinks()
    {
        $userLinkRepo = new UserLinkRepo();
        $userLinks = $userLinkRepo->getByUser($this->user);
        foreach ($userLinks AS $userLink)
            $userLinkRepo->remove($userLink);
    }

    private function removeToken()
    {
        (new TerminateAccountTokenRepo())->remove($this->token);
    }

    private function removeUser()
    {
        (new UserRepo())->remove($this->user);
    }

    private function logoutUser()
    {
        $authUser = new Auth();
        $authUser->removeUserFromSession($this->user);
    }
}