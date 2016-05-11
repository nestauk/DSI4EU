<?php

namespace DSI\Controller;

use DSI\Entity\Organisation;
use DSI\Entity\Project;
use DSI\Entity\User;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\ProjectMemberRepository;
use DSI\Repository\ProjectMemberRequestRepository;
use DSI\Repository\ProjectTagRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\UpdateOrganisation;
use DSI\UseCase\UpdateProject;

class OrganisationController
{
    /** @var  OrganisationController_Data */
    private $data;

    public function __construct()
    {
        $this->data = new OrganisationController_Data();
    }

    public function exec()
    {
        $loggedInUser = null;

        $authUser = new Auth();
        if ($authUser->isLoggedIn()) {
            $userRepo = new UserRepository();
            $loggedInUser = $userRepo->getById($authUser->getUserId());
        }

        $organisationRepo = new OrganisationRepository();
        $organisation = $organisationRepo->getById($this->data()->organisationID);

        try {
            if (isset($_POST['updateBasic'])) {
                $authUser->ifNotLoggedInRedirectTo(URL::login());

                $updateOrganisation = new UpdateOrganisation();
                $updateOrganisation->data()->organisation = $organisation;
                $updateOrganisation->data()->user = $loggedInUser;
                if (isset($_POST['name']))
                    $updateOrganisation->data()->name = $_POST['name'];
                if (isset($_POST['description']))
                    $updateOrganisation->data()->description = $_POST['description'];

                $updateOrganisation->exec();
                echo json_encode(['result' => 'ok']);
                die();
            }

            /*
            if (isset($_POST['addTag'])) {
                $addTagToProject = new AddTagToProject();
                $addTagToProject->data()->projectID = $organisation->getId();
                $addTagToProject->data()->tag = $_POST['addTag'];
                $addTagToProject->exec();
                echo json_encode(['result' => 'ok']);
                die();
            }
            if (isset($_POST['removeTag'])) {
                $removeTagFromProject = new RemoveTagFromProject();
                $removeTagFromProject->data()->projectID = $organisation->getId();
                $removeTagFromProject->data()->tag = $_POST['removeTag'];
                $removeTagFromProject->exec();
                echo json_encode(['result' => 'ok']);
                die();
            }

            if (isset($_POST['addImpactTagA'])) {
                $addTagToProject = new AddImpactTagAToProject();
                $addTagToProject->data()->projectID = $organisation->getId();
                $addTagToProject->data()->tag = $_POST['addImpactTagA'];
                $addTagToProject->exec();
                echo json_encode(['result' => 'ok']);
                die();
            }
            if (isset($_POST['removeImpactTagA'])) {
                $removeTagFromProject = new RemoveImpactTagAFromProject();
                $removeTagFromProject->data()->projectID = $organisation->getId();
                $removeTagFromProject->data()->tag = $_POST['removeImpactTagA'];
                $removeTagFromProject->exec();
                echo json_encode(['result' => 'ok']);
                die();
            }

            if (isset($_POST['addImpactTagB'])) {
                $addTagToProject = new AddImpactTagBToProject();
                $addTagToProject->data()->projectID = $organisation->getId();
                $addTagToProject->data()->tag = $_POST['addImpactTagB'];
                $addTagToProject->exec();
                echo json_encode(['result' => 'ok']);
                die();
            }
            if (isset($_POST['removeImpactTagB'])) {
                $removeTagFromProject = new RemoveImpactTagBFromProject();
                $removeTagFromProject->data()->projectID = $organisation->getId();
                $removeTagFromProject->data()->tag = $_POST['removeImpactTagB'];
                $removeTagFromProject->exec();
                echo json_encode(['result' => 'ok']);
                die();
            }

            if (isset($_POST['addImpactTagC'])) {
                $addTagToProject = new AddImpactTagCToProject();
                $addTagToProject->data()->projectID = $organisation->getId();
                $addTagToProject->data()->tag = $_POST['addImpactTagC'];
                $addTagToProject->exec();
                echo json_encode(['result' => 'ok']);
                die();
            }
            if (isset($_POST['removeImpactTagC'])) {
                $removeTagFromProject = new RemoveImpactTagCFromProject();
                $removeTagFromProject->data()->projectID = $organisation->getId();
                $removeTagFromProject->data()->tag = $_POST['removeImpactTagC'];
                $removeTagFromProject->exec();
                echo json_encode(['result' => 'ok']);
                die();
            }

            if (isset($_POST['addMember'])) {
                $addMemberToProject = new AddMemberToProject();
                $addMemberToProject->data()->projectID = $organisation->getId();
                $addMemberToProject->data()->userID = $_POST['addMember'];
                $addMemberToProject->exec();
                echo json_encode(['result' => 'ok']);
                die();
            }
            if (isset($_POST['removeMember'])) {
                $removeMemberFromProject = new RemoveMemberFromProject();
                $removeMemberFromProject->data()->projectID = $organisation->getId();
                $removeMemberFromProject->data()->userID = $_POST['removeMember'];
                $removeMemberFromProject->exec();
                echo json_encode(['result' => 'ok']);
                die();
            }

            if (isset($_POST['requestToJoin'])) {
                $addMemberRequestToJoinProject = new AddMemberRequestToProject();
                $addMemberRequestToJoinProject->data()->projectID = $organisation->getId();
                $addMemberRequestToJoinProject->data()->userID = $loggedInUser->getId();
                $addMemberRequestToJoinProject->exec();
                echo json_encode(['result' => 'ok']);
                die();
            }
            if (isset($_POST['approveRequestToJoin'])) {
                $approveMemberRequestToJoinProject = new ApproveMemberRequestToProject();
                $approveMemberRequestToJoinProject->data()->projectID = $organisation->getId();
                $approveMemberRequestToJoinProject->data()->userID = $_POST['approveRequestToJoin'];
                $approveMemberRequestToJoinProject->exec();
                echo json_encode(['result' => 'ok']);
                die();
            }
            if (isset($_POST['rejectRequestToJoin'])) {
                $rejectMemberRequestToJoinProject = new RejectMemberRequestToProject();
                $rejectMemberRequestToJoinProject->data()->projectID = $organisation->getId();
                $rejectMemberRequestToJoinProject->data()->userID = $_POST['rejectRequestToJoin'];
                $rejectMemberRequestToJoinProject->exec();
                echo json_encode(['result' => 'ok']);
                die();
            }

            if (isset($_POST['updateCountryRegion'])) {
                $updateProjectCountryRegionCmd = new UpdateProjectCountryRegion();
                $updateProjectCountryRegionCmd->data()->projectID = $organisation->getId();
                $updateProjectCountryRegionCmd->data()->countryID = $_POST['countryID'];
                $updateProjectCountryRegionCmd->data()->region = $_POST['region'];
                $updateProjectCountryRegionCmd->exec();
                echo json_encode(['result' => 'ok']);
                die();
            }
            */

        } catch (ErrorHandler $e) {
            echo json_encode([
                'result' => 'error',
                'errors' => $e->getErrors()
            ]);
            die();
        }

        $projectMembers = [];
        $memberRequests = [];
        $isOwner = false;
        $canUserRequestMembership = false;

        // $projectMembers = (new ProjectMemberRepository())->getMembersForProject($organisation->getId());
        if ($loggedInUser) {
            // $canUserRequestMembership = $this->canUserRequestMembership($organisation, $loggedInUser);
            if ($organisation->getOwner()->getId() == $loggedInUser->getId())
                $isOwner = true;

            //if (isset($isOwner) AND $isOwner === true)
            //  $memberRequests = (new ProjectMemberRequestRepository())->getMembersForProject($organisation->getId());
        }

        if ($this->data()->format == 'json') {
            echo json_encode([
                'name' => $organisation->getName(),
                'description' => $organisation->getDescription(),
                'tags' => (new ProjectTagRepository())->getTagsNameByProjectID($organisation->getId()),
                'members' => array_map(function (User $user) {
                    return [
                        'id' => $user->getId(),
                        'text' => $user->getFirstName() . ' ' . $user->getLastName(),
                        'firstName' => $user->getFirstName(),
                        'lastName' => $user->getLastName(),
                        'profilePic' => $user->getProfilePicOrDefault()
                    ];
                }, $projectMembers),
                'memberRequests' => array_map(function (User $user) {
                    return [
                        'id' => $user->getId(),
                        'text' => $user->getFirstName() . ' ' . $user->getLastName(),
                        'firstName' => $user->getFirstName(),
                        'lastName' => $user->getLastName(),
                        'profilePic' => $user->getProfilePicOrDefault()
                    ];
                }, $memberRequests),
                'countryID' => $organisation->getCountryID(),
                'countryRegionID' => $organisation->getCountryRegionID(),
                'countryRegion' => $organisation->getCountryRegion() ? $organisation->getCountryRegion()->getName() : '',
            ]);
            die();
        } else {
            $data = [
                'organisation' => $organisation,
                'canUserRequestMembership' => $canUserRequestMembership ?? false,
                'isOwner' => $isOwner ?? false,
            ];
            require __DIR__ . '/../../../www/organisation.php';
        }
    }

    /**
     * @return OrganisationController_Data
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @param Project $organisation
     * @param User $loggedInUser
     * @return bool
     */
    private function canUserRequestMembership(Organisation $organisation, User $loggedInUser)
    {
        if ($organisation->getOwner()->getId() == $loggedInUser->getId())
            return false;
        if ((new ProjectMemberRepository())->projectHasMember($organisation->getId(), $loggedInUser->getId()))
            return false;
        if ((new ProjectMemberRequestRepository())->projectHasRequestFromMember($organisation->getId(), $loggedInUser->getId()))
            return false;

        return true;
    }
}

class OrganisationController_Data
{
    /** @var  int */
    public $organisationID;

    /** @var string */
    public $format = 'html';
}