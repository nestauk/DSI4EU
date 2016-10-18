<?php

namespace DSI\UseCase\Projects;

use DSI\Entity\Project;
use DSI\Entity\User;
use DSI\Repository\ProjectDsiFocusTagRepository;
use DSI\Repository\ProjectEmailInvitationRepository;
use DSI\Repository\ProjectImpactTagARepository;
use DSI\Repository\ProjectImpactTagCRepository;
use DSI\Repository\ProjectLinkRepository;
use DSI\Repository\ProjectMemberInvitationRepository;
use DSI\Repository\ProjectMemberRepository;
use DSI\Repository\ProjectMemberRequestRepository;
use DSI\Repository\ProjectPostRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\ProjectTagRepository;
use DSI\Service\ErrorHandler;

class RemoveProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectRepository */
    private $projectRepository;

    /** @var Remove_Data */
    private $data;

    public function __construct()
    {
        $this->data = new Remove_Data();

        $this->errorHandler = new ErrorHandler();
        $this->projectRepository = new ProjectRepository();
    }

    public function exec()
    {
        $this->assertDataHasBeenSent();
        $this->assertExecutorCanMakeChanges();
        $this->removeProjectData();
        $this->removeProject();
    }

    /**
     * @return Remove_Data
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return bool
     */
    private function userCanDeleteProject()
    {
        if($this->data()->executor->isSysAdmin())
            return true;

        if($this->data()->executor->getId() == $this->data()->project->getOwnerID())
            return true;

        return false;
    }

    private function assertExecutorCanMakeChanges()
    {
        if (!$this->userCanDeleteProject()) {
            $this->errorHandler->addTaggedError('user', 'You cannot change member status');
            throw $this->errorHandler;
        }
    }

    private function assertDataHasBeenSent()
    {
        if (!$this->data()->executor)
            throw new \InvalidArgumentException('No executor');
        if (!$this->data()->project)
            throw new \InvalidArgumentException('No project');
    }

    private function removeProjectData()
    {
        $this->removeProjectEmailInvitations();
        $this->removeProjectInvitations();
        $this->removeProjectRequests();
        $this->removeProjectMembers();
        $this->removeProjectPosts();

        $this->removeProjectTags();
        $this->removeProjectTagsA();
        $this->removeProjectImpactTags();
        $this->removeProjectTagsC();
        $this->removeProjectLinks();
    }

    private function removeProject()
    {
        $this->projectRepository->remove($this->data()->project);
    }

    private function removeProjectEmailInvitations()
    {
        $projectEmailInvitationRepo = new ProjectEmailInvitationRepository();
        $projectInvitations = $projectEmailInvitationRepo->getByProjectID($this->data()->project->getId());
        foreach ($projectInvitations AS $invitation) {
            $projectEmailInvitationRepo->remove($invitation);
        }
    }

    private function removeProjectInvitations()
    {
        $projectMemberInvitationRepo = new ProjectMemberInvitationRepository();
        $projectInvitations = $projectMemberInvitationRepo->getByProjectID($this->data()->project->getId());
        foreach ($projectInvitations AS $invitation) {
            $projectMemberInvitationRepo->remove($invitation);
        }
    }

    private function removeProjectRequests()
    {
        $projectMemberRequestRepo = new ProjectMemberRequestRepository();
        $projectRequests = $projectMemberRequestRepo->getByProjectID($this->data()->project->getId());
        foreach ($projectRequests AS $request) {
            $projectMemberRequestRepo->remove($request);
        }
    }

    private function removeProjectMembers()
    {
        $projectMemberRepo = new ProjectMemberRepository();
        $projectMembers = $projectMemberRepo->getByProjectID($this->data()->project->getId());
        foreach ($projectMembers AS $projectMember) {
            $projectMemberRepo->remove($projectMember);
        }
    }

    private function removeProjectPosts()
    {
        $projectPostRepo = new ProjectPostRepository();
        $projectPosts = $projectPostRepo->getByProjectID($this->data()->project->getId());
        foreach ($projectPosts AS $projectPost) {
            $projectPostRepo->remove($projectPost);
        }
    }

    private function removeProjectTags()
    {
        $projectTagRepo = new ProjectTagRepository();
        $projectTags = $projectTagRepo->getByProjectID($this->data()->project->getId());
        foreach ($projectTags AS $projectTag) {
            $projectTagRepo->remove($projectTag);
        }
    }

    private function removeProjectTagsA()
    {
        $projectTagARepo = new ProjectImpactTagARepository();
        $projectTags = $projectTagARepo->getByProjectID($this->data()->project->getId());
        foreach ($projectTags AS $projectTag) {
            $projectTagARepo->remove($projectTag);
        }
    }

    private function removeProjectImpactTags()
    {
        $projectDsiTagRepo = new ProjectDsiFocusTagRepository();
        $projectTags = $projectDsiTagRepo->getByProjectID($this->data()->project->getId());
        foreach ($projectTags AS $projectTag) {
            $projectDsiTagRepo->remove($projectTag);
        }
    }

    private function removeProjectTagsC()
    {
        $projectTagCRepo = new ProjectImpactTagCRepository();
        $projectTags = $projectTagCRepo->getByProjectID($this->data()->project->getId());
        foreach ($projectTags AS $projectTag) {
            $projectTagCRepo->remove($projectTag);
        }
    }

    private function removeProjectLinks()
    {
        $projectLinkRepo = new ProjectLinkRepository();
        $projectLinks = $projectLinkRepo->getByProjectID($this->data()->project->getId());
        foreach ($projectLinks AS $projectLink) {
            $projectLinkRepo->remove($projectLink);
        }
    }
}

class Remove_Data
{
    /** @var Project */
    public $project;

    /** @var User */
    public $executor;
}