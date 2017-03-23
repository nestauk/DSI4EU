<?php

namespace DSI\Controller;

use DSI\Entity\User;
use DSI\Repository\OrganisationMemberInvitationRepository;
use DSI\Repository\OrganisationMemberRepository;
use DSI\Repository\OrganisationMemberRequestRepository;
use DSI\Repository\ProjectMemberInvitationRepository;
use DSI\Repository\ProjectMemberRepository;
use DSI\Repository\ProjectMemberRequestRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;

class NotificationsController
{
    public $format = 'html';

    /** @var URL */
    private $urlHandler;

    public function exec()
    {
        $this->urlHandler = $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());
        $loggedInUser = $authUser->getUser();

        try {
            $projectInvitations = (new ProjectMemberInvitationRepository())->getByMemberID($loggedInUser->getId());
            $projectRequests = $this->getProjectRequests($loggedInUser);
            $organisationInvitations = (new OrganisationMemberInvitationRepository())->getByMemberID($loggedInUser->getId());
            $organisationRequests = $this->getOrganisationRequests($loggedInUser);

            echo json_encode([
                'code' => 'ok',
                'notifications' =>
                    count($projectInvitations) +
                    count($projectRequests) +
                    count($organisationInvitations) +
                    count($organisationRequests)
            ]);
            return null;
        } catch (ErrorHandler $e) {
            echo json_encode([
                'code' => 'error',
                'errors' => $e->getErrors()
            ]);
        }

        return null;
    }

    /**
     * @param User $loggedInUser
     * @return \DSI\Entity\OrganisationMemberRequest[]
     */
    private function getOrganisationRequests(User $loggedInUser)
    {
        $organisationsWhereUserIsAdmin = (new OrganisationMemberRepository())->getByAdmin($loggedInUser);
        if ($organisationsWhereUserIsAdmin) {
            $_orgIDs = [];
            foreach ($organisationsWhereUserIsAdmin AS $_org)
                $_orgIDs[] = $_org->getOrganisationID();

            $organisationRequests = (new OrganisationMemberRequestRepository())->getByOrganisationIDs($_orgIDs);
        } else {
            $organisationRequests = [];
        }
        return $organisationRequests;
    }

    /**
     * @param User $loggedInUser
     * @return \DSI\Entity\ProjectMemberRequest[]
     */
    private function getProjectRequests(User $loggedInUser)
    {
        $projectsWhereUserIsAdmin = (new ProjectMemberRepository())->getByAdmin($loggedInUser);
        if ($projectsWhereUserIsAdmin) {
            $_projectIDs = [];
            foreach ($projectsWhereUserIsAdmin AS $_project)
                $_projectIDs[] = $_project->getProjectID();

            $projectRequests = (new ProjectMemberRequestRepository())->getByProjectIDs($_projectIDs);
        } else {
            $projectRequests = [];
        }
        return $projectRequests;
    }
}