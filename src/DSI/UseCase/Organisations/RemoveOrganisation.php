<?php

namespace DSI\UseCase\Organisations;

use DSI\Entity\Organisation;
use DSI\Entity\User;
use DSI\Repository\OrganisationLinkRepository;
use DSI\Repository\OrganisationMemberInvitationRepository;
use DSI\Repository\OrganisationMemberRepository;
use DSI\Repository\OrganisationMemberRequestRepository;
use DSI\Repository\OrganisationProjectRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\OrganisationTagRepository;
use DSI\Service\ErrorHandler;

class RemoveOrganisation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationRepository */
    private $organisationRepo;

    /** @var RemoveOrganisation_Data */
    private $data;

    public function __construct()
    {
        $this->data = new RemoveOrganisation_Data();

        $this->errorHandler = new ErrorHandler();
        $this->organisationRepo = new OrganisationRepository();
    }

    public function exec()
    {
        $this->assertDataHasBeenSent();
        $this->assertExecutorCanMakeChanges();
        $this->removeOrganisationData();
        $this->removeOrganisation();
    }

    /**
     * @return RemoveOrganisation_Data
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return bool
     */
    private function userCanDeleteOrganisation()
    {
        if($this->data()->executor->isSysAdmin())
            return true;

        if($this->data()->executor->getId() == $this->data()->organisation->getOwnerID())
            return true;

        return false;
    }

    private function assertExecutorCanMakeChanges()
    {
        if (!$this->userCanDeleteOrganisation()) {
            $this->errorHandler->addTaggedError('user', 'You cannot delete this organisation');
            throw $this->errorHandler;
        }
    }

    private function assertDataHasBeenSent()
    {
        if (!$this->data()->executor)
            throw new \InvalidArgumentException('No executor');
        if (!$this->data()->organisation)
            throw new \InvalidArgumentException('No organisation');
    }

    private function removeOrganisationData()
    {
        $this->removeOrgLinks();
        $this->removeOrgInvitations();
        $this->removeOrgRequests();
        $this->removeOrgMembers();
        $this->removeOrgProjects();
        $this->removeOrgTags();
    }

    private function removeOrganisation()
    {
        $this->organisationRepo->remove($this->data()->organisation);
    }

    private function removeOrgInvitations()
    {
        $orgMemberInvitationRepo = new OrganisationMemberInvitationRepository();
        $orgInvitations = $orgMemberInvitationRepo->getByOrganisationID($this->data()->organisation->getId());
        foreach ($orgInvitations AS $invitation) {
            $orgMemberInvitationRepo->remove($invitation);
        }
    }

    private function removeOrgRequests()
    {
        $orgMemberRequestRepo = new OrganisationMemberRequestRepository();
        $orgRequests = $orgMemberRequestRepo->getByOrganisationID($this->data()->organisation->getId());
        foreach ($orgRequests AS $request) {
            $orgMemberRequestRepo->remove($request);
        }
    }

    private function removeOrgMembers()
    {
        $orgMemberRepo = new OrganisationMemberRepository();
        $orgMembers = $orgMemberRepo->getByOrganisationID($this->data()->organisation->getId());
        foreach ($orgMembers AS $member) {
            $orgMemberRepo->remove($member);
        }
    }

    private function removeOrgProjects()
    {
        $orgProjectsRepo = new OrganisationProjectRepository();
        $orgProjects = $orgProjectsRepo->getByOrganisationID($this->data()->organisation->getId());
        foreach ($orgProjects AS $orgProject) {
            $orgProjectsRepo->remove($orgProject);
        }
    }

    private function removeOrgTags()
    {
        $orgTagRepo = new OrganisationTagRepository();
        $orgTags = $orgTagRepo->getByOrganisationID($this->data()->organisation->getId());
        foreach ($orgTags AS $orgTag) {
            $orgTagRepo->remove($orgTag);
        }
    }

    private function removeOrgLinks()
    {
        $orgLinkRepo = new OrganisationLinkRepository();
        $orgLinks = $orgLinkRepo->getByOrganisationID($this->data()->organisation->getId());
        foreach ($orgLinks AS $orgLink) {
            $orgLinkRepo->remove($orgLink);
        }
    }
}

class RemoveOrganisation_Data
{
    /** @var Organisation */
    public $organisation;

    /** @var User */
    public $executor;
}