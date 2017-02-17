<?php

namespace DSI\Controller;

use DSI\Entity\Organisation;
use DSI\Entity\OrganisationMember;
use DSI\Entity\OrganisationMemberInvitation;
use DSI\Entity\User;
use DSI\Repository\OrganisationMemberInvitationRepository;
use DSI\Repository\OrganisationMemberRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\JsModules;
use DSI\Service\URL;
use DSI\UseCase\AddMemberInvitationToOrganisation;
use DSI\UseCase\RemoveMemberFromOrganisation;
use DSI\UseCase\RemoveMemberFromProject;
use DSI\UseCase\SearchUser;
use DSI\UseCase\SetAdminStatusToOrganisationMember;

class OrganisationEditMembersController
{
    /** @var  int */
    public $organisationID;

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

        $organisationRepo = new OrganisationRepository();
        $organisation = $organisationRepo->getById($this->organisationID);

        $members = (new OrganisationMemberRepository())->getByOrganisation($organisation);
        $isOwner = $this->isOrganisationOwner($organisation, $loggedInUser);
        $isAdmin = $this->isOrganisationAdmin($members, $loggedInUser);

        if (!$isOwner AND !$isAdmin AND !$loggedInUser->isSysAdmin())
            go_to($urlHandler->home());

        try {
            if (isset($_POST['searchExistingUser']))
                return $this->searchExistingUser($_POST['searchExistingUser']);

            if (isset($_POST['addExistingUser']))
                return $this->addExistingUser($organisation, $_POST['addExistingUser']);

            if (isset($_POST['cancelUserInvitation']))
                return $this->cancelUserInvitation($organisation, $_POST['cancelUserInvitation']);

            if (isset($_POST['removeMember']))
                return $this->removeMember($organisation, $_POST['removeMember']);

            if (isset($_POST['makeAdmin']))
                return $this->makeAdmin($organisation, $_POST['makeAdmin']);

            if (isset($_POST['removeAdmin']))
                return $this->removeAdmin($organisation, $_POST['removeAdmin']);

        } catch (ErrorHandler $e) {
            echo json_encode([
                'code' => 'error',
                'errors' => $e->getErrors(),
            ]);
            return true;
        }

        if ($this->format == 'json') {
            echo json_encode([
                'members' => $this->organisationMembersJson($members),
                'invitedMembers' => $this->organisationInvitedMembersJson($organisation),
            ]);
        } else {
            $pageTitle = $organisation->getName();
            JsModules::setTranslations(true);
            require __DIR__ . '/../../../www/views/organisation-edit-members.php';
        }

        return null;
    }

    /**
     * @param Organisation $organisation
     * @param User $user
     * @return bool
     */
    private function isOrganisationOwner(Organisation $organisation, User $user): bool
    {
        return $organisation->getOwner()->getId() == $user->getId();
    }

    /**
     * @param OrganisationMember[] $members
     * @param User $user
     * @return bool
     */
    private function isOrganisationAdmin($members, User $user): bool
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
     * @param OrganisationMember[] $members
     * @return array
     */
    private function organisationMembersJson($members)
    {
        return array_map(function (OrganisationMember $member) {
            $user = $member->getMember();
            $organisation = $member->getOrganisation();
            return [
                'id' => $user->getId(),
                'name' => $user->getFullName(),
                'jobTitle' => $user->getJobTitle(),
                'profilePic' => $user->getProfilePic(),
                'isAdmin' => $member->isAdmin(),
                'isOwner' => $member->getMemberID() == $organisation->getOwnerID(),
            ];
        }, $members);
    }

    /**
     * @param Organisation $organisation
     * @return array
     */
    private function organisationInvitedMembersJson(Organisation $organisation)
    {
        $invitedMembers = (new OrganisationMemberInvitationRepository())->getByOrganisation($organisation);
        return array_map(function (OrganisationMemberInvitation $invitedMember) {
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
     * @param Organisation $organisation
     * @param int $userID
     * @return bool
     */
    private function addExistingUser(Organisation $organisation, $userID)
    {
        $exec = new AddMemberInvitationToOrganisation();
        $exec->setOrganisation($organisation);
        $exec->setUserID($userID);
        $exec->exec();

        echo json_encode([
            'code' => 'ok',
        ]);

        return true;
    }

    /**
     * @param Organisation $organisation
     * @param int $userID
     * @return bool
     */
    private function cancelUserInvitation(Organisation $organisation, $userID)
    {
        $exec = new RemoveMemberFromProject();
        $exec->setProjectID($organisation->getId());
        $exec->setUserID($userID);
        $exec->exec();

        echo json_encode([
            'code' => 'ok',
        ]);

        return true;
    }

    /**
     * @param Organisation $organisation
     * @param int $userID
     * @return bool
     */
    private function removeMember(Organisation $organisation, int $userID)
    {
        $exec = new RemoveMemberFromOrganisation();
        $exec->setOrganisation($organisation);
        $exec->setUserId($userID);
        $exec->exec();

        echo json_encode([
            'code' => 'ok',
        ]);

        return true;
    }

    /**
     * @param Organisation $organisation
     * @param int $userID
     * @return bool
     */
    private function makeAdmin(Organisation $organisation, int $userID)
    {
        $exec = new SetAdminStatusToOrganisationMember();
        $exec->setOrganisation($organisation);
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
     * @param Organisation $organisation
     * @param int $userID
     * @return bool
     */
    private function removeAdmin(Organisation $organisation, int $userID)
    {
        $exec = new SetAdminStatusToOrganisationMember();
        $exec->setOrganisation($organisation);
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