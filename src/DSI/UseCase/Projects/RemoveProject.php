<?php

namespace DSI\UseCase\Projects;

use DSI\Entity\Project;
use DSI\Entity\User;
use DSI\Repository\OrganisationProjectRepo;
use DSI\Repository\ProjectDsiFocusTagRepo;
use DSI\Repository\ProjectEmailInvitationRepo;
use DSI\Repository\ProjectImpactHelpTagRepo;
use DSI\Repository\ProjectImpactTechTagRepo;
use DSI\Repository\ProjectLinkRepo;
use DSI\Repository\ProjectMemberInvitationRepo;
use DSI\Repository\ProjectMemberRepo;
use DSI\Repository\ProjectMemberRequestRepo;
use DSI\Repository\ProjectPostRepo;
use DSI\Repository\ProjectRepo;
use DSI\Repository\ProjectRepoInAPC;
use DSI\Repository\ProjectTagRepo;
use DSI\Service\ErrorHandler;

class RemoveProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectRepo */
    private $projectRepository;

    /** @var Remove_Data */
    private $data;

    public function __construct()
    {
        $this->data = new Remove_Data();

        $this->errorHandler = new ErrorHandler();
        $this->projectRepository = new ProjectRepoInAPC();
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
        $this->removeProjectsOrganisations();

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
        $projectEmailInvitationRepo = new ProjectEmailInvitationRepo();
        $projectInvitations = $projectEmailInvitationRepo->getByProject($this->data()->project);
        foreach ($projectInvitations AS $invitation) {
            $projectEmailInvitationRepo->remove($invitation);
        }
    }

    private function removeProjectInvitations()
    {
        $projectMemberInvitationRepo = new ProjectMemberInvitationRepo();
        $projectInvitations = $projectMemberInvitationRepo->getByProject($this->data()->project);
        foreach ($projectInvitations AS $invitation) {
            $projectMemberInvitationRepo->remove($invitation);
        }
    }

    private function removeProjectRequests()
    {
        $projectMemberRequestRepo = new ProjectMemberRequestRepo();
        $projectRequests = $projectMemberRequestRepo->getByProjectID($this->data()->project->getId());
        foreach ($projectRequests AS $request) {
            $projectMemberRequestRepo->remove($request);
        }
    }

    private function removeProjectMembers()
    {
        $projectMemberRepo = new ProjectMemberRepo();
        $projectMembers = $projectMemberRepo->getByProject($this->data()->project);
        foreach ($projectMembers AS $projectMember) {
            $projectMemberRepo->remove($projectMember);
        }
    }

    private function removeProjectPosts()
    {
        $projectPostRepo = new ProjectPostRepo();
        $projectPosts = $projectPostRepo->getByProjectID($this->data()->project->getId());
        foreach ($projectPosts AS $projectPost) {
            $projectPostRepo->remove($projectPost);
        }
    }

    private function removeProjectsOrganisations()
    {
        $orgProjectsRepo = new OrganisationProjectRepo();
        $orgProjects = $orgProjectsRepo->getByProjectID($this->data()->project->getId());
        foreach ($orgProjects AS $orgProject) {
            $orgProjectsRepo->remove($orgProject);
        }
    }

    private function removeProjectTags()
    {
        $projectTagRepo = new ProjectTagRepo();
        $projectTags = $projectTagRepo->getByProjectID($this->data()->project->getId());
        foreach ($projectTags AS $projectTag) {
            $projectTagRepo->remove($projectTag);
        }
    }

    private function removeProjectTagsA()
    {
        $projectTagARepo = new ProjectImpactHelpTagRepo();
        $projectTags = $projectTagARepo->getByProjectID($this->data()->project->getId());
        foreach ($projectTags AS $projectTag) {
            $projectTagARepo->remove($projectTag);
        }
    }

    private function removeProjectImpactTags()
    {
        $projectDsiTagRepo = new ProjectDsiFocusTagRepo();
        $projectTags = $projectDsiTagRepo->getByProjectID($this->data()->project->getId());
        foreach ($projectTags AS $projectTag) {
            $projectDsiTagRepo->remove($projectTag);
        }
    }

    private function removeProjectTagsC()
    {
        $projectTagCRepo = new ProjectImpactTechTagRepo();
        $projectTags = $projectTagCRepo->getByProjectID($this->data()->project->getId());
        foreach ($projectTags AS $projectTag) {
            $projectTagCRepo->remove($projectTag);
        }
    }

    private function removeProjectLinks()
    {
        $projectLinkRepo = new ProjectLinkRepo();
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