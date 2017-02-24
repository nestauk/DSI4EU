<?php

namespace DSI\UseCase\TerminateAccount;

use DSI\Entity\TerminateAccountToken;
use DSI\Entity\User;
use DSI\Repository\OrganisationFollowRepository;
use DSI\Repository\OrganisationMemberInvitationRepository;
use DSI\Repository\OrganisationMemberRepository;
use DSI\Repository\OrganisationMemberRequestRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\ProjectFollowRepository;
use DSI\Repository\ProjectMemberInvitationRepository;
use DSI\Repository\ProjectMemberRepository;
use DSI\Repository\ProjectMemberRequestRepository;
use DSI\Repository\ProjectPostCommentReplyRepository;
use DSI\Repository\ProjectPostCommentRepository;
use DSI\Repository\ProjectPostRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\TerminateAccountTokenRepository;
use DSI\Repository\UserLinkRepository;
use DSI\Repository\UserRepository;
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

        $organisationRepo = new OrganisationRepository();
        $organisations = $organisationRepo->getByUser($this->user);
        foreach ($organisations AS $organisation) {
            $organisation->setIsPublished(false);
            $organisation->setOwner($sysAdmin);
            $organisationRepo->save($organisation);
        }

        $organisationFollowRepo = new OrganisationFollowRepository();
        $organisationFollows = $organisationFollowRepo->getByUser($this->user);
        foreach ($organisationFollows AS $organisationFollow)
            $organisationFollowRepo->remove($organisationFollow);

        $organisationMemberInvitationRepo = new OrganisationMemberInvitationRepository();
        $organisationMemberInvitations = $organisationMemberInvitationRepo->getByMemberID($this->user->getId());
        foreach ($organisationMemberInvitations AS $organisationMemberInvitation)
            $organisationMemberInvitationRepo->remove($organisationMemberInvitation);

        $organisationMemberRequestRepo = new OrganisationMemberRequestRepository();
        $organisationMemberRequests = $organisationMemberRequestRepo->getByMemberID($this->user->getId());
        foreach ($organisationMemberRequests AS $organisationMemberRequest)
            $organisationMemberRequestRepo->remove($organisationMemberRequest);

        $organisationMemberRepo = new OrganisationMemberRepository();
        $organisationMembers = $organisationMemberRepo->getByMemberID($this->user->getId());
        foreach ($organisationMembers AS $organisationMember)
            $organisationMemberRepo->remove($organisationMember);
    }

    private function updateProjects()
    {
        $sysAdmin = $this->getSysAdmin();

        $projectRepo = new ProjectRepository();
        $projects = $projectRepo->getByUser($this->user);
        foreach ($projects AS $project) {
            $project->setIsPublished(false);
            $project->setOwner($sysAdmin);
            $projectRepo->save($project);
        }

        $projectFollowRepo = new ProjectFollowRepository();
        $projectFollows = $projectFollowRepo->getByUser($this->user);
        foreach ($projectFollows as $projectFollow)
            $projectFollowRepo->remove($projectFollow);

        $projectMemberInvitationRepo = new ProjectMemberInvitationRepository();
        $projectMemberInvitations = $projectMemberInvitationRepo->getByMemberID($this->user->getId());
        foreach ($projectMemberInvitations as $projectMemberInvitation)
            $projectMemberInvitationRepo->remove($projectMemberInvitation);

        $projectMemberRequestRepo = new ProjectMemberRequestRepository();
        $projectMemberRequests = $projectMemberRequestRepo->getByMemberID($this->user->getId());
        foreach ($projectMemberRequests as $projectMemberRequest)
            $projectMemberRequestRepo->remove($projectMemberRequest);

        $projectMemberRepo = new ProjectMemberRepository();
        $projectMembers = $projectMemberRepo->getByMemberID($this->user->getId());
        foreach ($projectMembers as $projectMember)
            $projectMemberRepo->remove($projectMember);

        $projectPostCommentReplyRepo = new ProjectPostCommentReplyRepository();
        $replies = $projectPostCommentReplyRepo->getByUser($this->user);
        foreach ($replies as $reply)
            $projectPostCommentReplyRepo->remove($reply);

        $projectPostCommentRepo = new ProjectPostCommentRepository();
        $projectPostComments = $projectPostCommentRepo->getByUser($this->user);
        foreach ($projectPostComments AS $projectPostComment)
            $projectPostCommentRepo->save($projectPostComment);

        $projectPostRepo = new ProjectPostRepository();
        $projectPosts = $projectPostRepo->getByUser($this->user);
        foreach ($projectPosts AS $projectPost)
            $projectPostRepo->remove($projectPost);
    }

    /**
     * @return User
     */
    private function getSysAdmin(): User
    {
        return (new UserRepository())->getById(1);
    }

    private function removeUserLinks()
    {
        $userLinkRepo = new UserLinkRepository();
        $userLinks = $userLinkRepo->getByUser($this->user);
        foreach ($userLinks AS $userLink)
            $userLinkRepo->remove($userLink);
    }

    private function removeToken()
    {
        (new TerminateAccountTokenRepository())->remove($this->token);
    }

    private function removeUser()
    {
        (new UserRepository())->remove($this->user);
    }

    private function logoutUser()
    {
        $authUser = new Auth();
        $authUser->removeUserFromSession($this->user);
    }
}