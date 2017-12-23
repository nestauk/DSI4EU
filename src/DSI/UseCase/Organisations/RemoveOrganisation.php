<?php

namespace DSI\UseCase\Organisations;

use DSI\Entity\Organisation;
use DSI\Entity\User;
use DSI\Repository\OrganisationLinkRepo;
use DSI\Repository\OrganisationMemberInvitationRepo;
use DSI\Repository\OrganisationMemberRepo;
use DSI\Repository\OrganisationMemberRequestRepo;
use DSI\Repository\OrganisationProjectRepo;
use DSI\Repository\OrganisationRepo;
use DSI\Repository\OrganisationTagRepo;
use DSI\Service\ErrorHandler;

class RemoveOrganisation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationRepo */
    private $organisationRepo;

    /** @var RemoveOrganisation_Data */
    private $data;

    public function __construct()
    {
        $this->data = new RemoveOrganisation_Data();

        $this->errorHandler = new ErrorHandler();
        $this->organisationRepo = new OrganisationRepo();
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
        $orgMemberInvitationRepo = new OrganisationMemberInvitationRepo();
        $orgInvitations = $orgMemberInvitationRepo->getByOrganisation($this->data()->organisation);
        foreach ($orgInvitations AS $invitation) {
            $orgMemberInvitationRepo->remove($invitation);
        }
    }

    private function removeOrgRequests()
    {
        $orgMemberRequestRepo = new OrganisationMemberRequestRepo();
        $orgRequests = $orgMemberRequestRepo->getByOrganisationID($this->data()->organisation->getId());
        foreach ($orgRequests AS $request) {
            $orgMemberRequestRepo->remove($request);
        }
    }

    private function removeOrgMembers()
    {
        $orgMemberRepo = new OrganisationMemberRepo();
        $orgMembers = $orgMemberRepo->getByOrganisation($this->data()->organisation);
        foreach ($orgMembers AS $member) {
            $orgMemberRepo->remove($member);
        }
    }

    private function removeOrgProjects()
    {
        $orgProjectsRepo = new OrganisationProjectRepo();
        $orgProjects = $orgProjectsRepo->getByOrganisationID($this->data()->organisation->getId());
        foreach ($orgProjects AS $orgProject) {
            $orgProjectsRepo->remove($orgProject);
        }
    }

    private function removeOrgTags()
    {
        $orgTagRepo = new OrganisationTagRepo();
        $orgTags = $orgTagRepo->getByOrganisationID($this->data()->organisation->getId());
        foreach ($orgTags AS $orgTag) {
            $orgTagRepo->remove($orgTag);
        }
    }

    private function removeOrgLinks()
    {
        $orgLinkRepo = new OrganisationLinkRepo();
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