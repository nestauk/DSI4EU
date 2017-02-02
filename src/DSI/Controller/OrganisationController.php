<?php

namespace DSI\Controller;

use DSI\Entity\Organisation;
use DSI\Entity\OrganisationLink_Service;
use DSI\Entity\OrganisationNetworkTag;
use DSI\Entity\OrganisationTag;
use DSI\Entity\User;
use DSI\Repository\OrganisationFollowRepository;
use DSI\Repository\OrganisationLinkRepository;
use DSI\Repository\OrganisationMemberRepository;
use DSI\Repository\OrganisationMemberRequestRepository;
use DSI\Repository\OrganisationNetworkTagRepository;
use DSI\Repository\OrganisationProjectRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\OrganisationTagRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\Mailer;
use DSI\Service\URL;
use DSI\UseCase\AddMemberRequestToOrganisation;
use DSI\UseCase\CalculateOrganisationPartnersCount;
use DSI\UseCase\Organisations\FollowOrganisation;
use DSI\UseCase\Organisations\RemoveOrganisation;
use DSI\UseCase\Organisations\UnfollowOrganisation;
use DSI\UseCase\RejectMemberRequestToOrganisation;
use DSI\UseCase\RemoveMemberFromOrganisation;
use DSI\UseCase\SecureCode;
use DSI\UseCase\SendEmailToCommunityAdmins;

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
        $urlHandler = new URL();
        $loggedInUser = null;
        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();
        $organisationRepo = new OrganisationRepository();
        $organisation = $organisationRepo->getById($this->data()->organisationID);

        $this->updateOrganisationPartnersCount($organisation);

        $userIsMember = false;
        $userSentJoinRequest = false;
        $userCanSendJoinRequest = false;

        /*
        $organisationTypes = (new OrganisationTypeRepository())->getAll();
        $organisationSizes = (new OrganisationSizeRepository())->getAll();

        try {
            if (isset($_POST['updateBasic'])) {
                $authUser->ifNotLoggedInRedirectTo(URL::login());

                $updateOrganisation = new UpdateOrganisation();
                $updateOrganisation->data()->organisation = $organisation;
                $updateOrganisation->data()->executor = $loggedInUser;
                if (isset($_POST['name']))
                    $updateOrganisation->data()->name = $_POST['name'];
                if (isset($_POST['description']))
                    $updateOrganisation->data()->description = $_POST['description'];
                $updateOrganisation->data()->address = $_POST['address'];
                if (isset($_POST['organisationTypeId']) AND $_POST['organisationTypeId'])
                    $updateOrganisation->data()->organisationTypeId = $_POST['organisationTypeId'];
                if (isset($_POST['organisationSizeId']) AND $_POST['organisationSizeId'])
                    $updateOrganisation->data()->organisationSizeId = $_POST['organisationSizeId'];

                $updateOrganisation->exec();
                echo json_encode(['result' => 'ok']);
                return;
            }

            if (isset($_POST['addTag'])) {
                $addTagCmd = new AddTagToOrganisation();
                $addTagCmd->data()->organisationID = $organisation->getId();
                $addTagCmd->data()->tag = $_POST['addTag'];
                $addTagCmd->exec();
                echo json_encode(['result' => 'ok']);
                return;
            }
            if (isset($_POST['removeTag'])) {
                $removeTagCmd = new RemoveTagFromOrganisation();
                $removeTagCmd->data()->organisationID = $organisation->getId();
                $removeTagCmd->data()->tag = $_POST['removeTag'];
                $removeTagCmd->exec();
                echo json_encode(['result' => 'ok']);
                return;
            }

            if (isset($_POST['addMember'])) {
                $addMemberToOrgCmd = new AddMemberInvitationToOrganisation();
                $addMemberToOrgCmd->data()->organisationID = $organisation->getId();
                $addMemberToOrgCmd->data()->userID = $_POST['addMember'];
                $addMemberToOrgCmd->exec();
                echo json_encode(['result' => 'ok']);
                return;
            }
            if (isset($_POST['removeMember'])) {
                $removeMemberFromOrgCmd = new RemoveMemberFromOrganisation();
                $removeMemberFromOrgCmd->data()->organisationID = $organisation->getId();
                $removeMemberFromOrgCmd->data()->userID = $_POST['removeMember'];
                $removeMemberFromOrgCmd->exec();
                echo json_encode(['result' => 'ok']);
                return;
            }

            if (isset($_POST['requestToJoin'])) {
                $addMemberRequestToJoinOrganisation = new AddMemberRequestToOrganisation();
                $addMemberRequestToJoinOrganisation->data()->organisationID = $organisation->getId();
                $addMemberRequestToJoinOrganisation->data()->userID = $loggedInUser->getId();
                $addMemberRequestToJoinOrganisation->exec();
                echo json_encode(['result' => 'ok']);
                return;
            }
            if (isset($_POST['approveRequestToJoin'])) {
                $approveMemberRequestToJoinOrganisation = new ApproveMemberRequestToOrganisation();
                $approveMemberRequestToJoinOrganisation->data()->organisationID = $organisation->getId();
                $approveMemberRequestToJoinOrganisation->data()->userID = $_POST['approveRequestToJoin'];
                $approveMemberRequestToJoinOrganisation->exec();
                echo json_encode(['result' => 'ok']);
                return;
            }
            if (isset($_POST['rejectRequestToJoin'])) {
                $rejectMemberRequestToJoinOrganisation = new RejectMemberRequestToOrganisation();
                $rejectMemberRequestToJoinOrganisation->data()->organisationID = $organisation->getId();
                $rejectMemberRequestToJoinOrganisation->data()->userID = $_POST['rejectRequestToJoin'];
                $rejectMemberRequestToJoinOrganisation->exec();
                echo json_encode(['result' => 'ok']);
                return;
            }

            if (isset($_POST['updateCountryRegion'])) {
                $createProjectCmd = new UpdateOrganisationCountryRegion();
                $createProjectCmd->data()->organisationID = $organisation->getId();
                $createProjectCmd->data()->countryID = $_POST['countryID'];
                $createProjectCmd->data()->region = $_POST['region'];
                $createProjectCmd->exec();
                echo json_encode(['result' => 'ok']);
                return;
            }

            if (isset($_POST['createProject'])) {
                $createProjectCmd = new CreateProject();
                $createProjectCmd->data()->name = $_POST['createProject'];
                $createProjectCmd->data()->owner = $loggedInUser;
                $createProjectCmd->exec();
                $project = $createProjectCmd->getProject();

                $addOrganisationProjectCmd = new AddProjectToOrganisation();
                $addOrganisationProjectCmd->data()->projectID = $project->getId();
                $addOrganisationProjectCmd->data()->organisationID = $organisation->getId();
                $addOrganisationProjectCmd->exec();

                echo json_encode([
                    'result' => 'ok',
                    'url' => URL::project($project),
                ]);
                return;
            }

        } catch (ErrorHandler $e) {
            echo json_encode([
                'result' => 'error',
                'errors' => $e->getErrors()
            ]);
            return;
        }
        */

        $memberRequests = [];
        $isOwner = false;
        $canUserRequestMembership = false;
        $userCanEditOrganisation = false;

        $organisationMembers = (new OrganisationMemberRepository())->getMembersForOrganisation($organisation);
        $organisationProjects = (new OrganisationProjectRepository())->getByOrganisationID($organisation->getId());
        $partnerOrganisations = (new OrganisationProjectRepository())->getPartnerOrganisationsFor($organisation);

        if ($loggedInUser) {
            $canUserRequestMembership = $this->canUserRequestMembership($organisation, $loggedInUser);
            if ($organisation->getOwnerID() == $loggedInUser->getId())
                $isOwner = true;

            if (isset($isOwner) AND $isOwner === true)
                $memberRequests = (new OrganisationMemberRequestRepository())->getMembersForOrganisation($organisation);

            // $userCanEditOrganisation = ($isAdmin OR ($loggedInUser AND $loggedInUser->isCommunityAdmin()));
            $userCanEditOrganisation = ($isOwner OR ($loggedInUser AND $loggedInUser->isCommunityAdmin()));
            $userIsFollowing = (
                $loggedInUser AND
                (new OrganisationFollowRepository())->userFollowsOrganisation($loggedInUser, $organisation)
            );

            if (isset($_POST['getSecureCode']))
                return $this->setSecureCode();

            if (isset($_POST['reportOrganisation']))
                return $this->report($loggedInUser, $organisation, $urlHandler);

            if (isset($_POST['deleteOrganisation']))
                return $this->deleteOrganisation($loggedInUser, $organisation, $urlHandler);

            if (isset($_POST['joinOrganisation']))
                return $this->joinOrganisation($loggedInUser, $organisation);

            if (isset($_POST['cancelJoinRequest']))
                return $this->cancelJoinRequest($loggedInUser, $organisation);

            if (isset($_POST['leaveOrganisation']))
                return $this->leaveOrganisation($loggedInUser, $organisation);

            if (isset($_POST['followOrganisation']))
                return $this->followOrganisation($loggedInUser, $organisation);

            if (isset($_POST['unfollowOrganisation']))
                return $this->unfollowOrganisation($loggedInUser, $organisation);

            $userIsMember = (new OrganisationMemberRepository())->organisationHasMember($organisation, $loggedInUser);
            if (!$userIsMember) {
                $userSentJoinRequest = (new OrganisationMemberRequestRepository())->organisationHasRequestFromMember(
                    $organisation->getId(),
                    $loggedInUser->getId()
                );
                if (!$userSentJoinRequest)
                    $userCanSendJoinRequest = true;
            }
        }

        $links = [];
        $organisationLinks = (new OrganisationLinkRepository())->getByOrganisationID($organisation->getId());
        foreach ($organisationLinks AS $organisationLink) {
            if ($organisationLink->getLinkService() == OrganisationLink_Service::Facebook)
                $links['facebook'] = $organisationLink->getLink();
            if ($organisationLink->getLinkService() == OrganisationLink_Service::Twitter)
                $links['twitter'] = $organisationLink->getLink();
            if ($organisationLink->getLinkService() == OrganisationLink_Service::GooglePlus)
                $links['googleplus'] = $organisationLink->getLink();
            if ($organisationLink->getLinkService() == OrganisationLink_Service::GitHub)
                $links['github'] = $organisationLink->getLink();
        }

        /*
        if ($this->data()->format == 'json') {
            $owner = $organisation->getOwner();
            echo json_encode([
                'name' => $organisation->getName(),
                'description' => $organisation->getDescription(),
                'address' => $organisation->getAddress(),
                'organisationTypeId' => (string)$organisation->getOrganisationTypeId(),
                'organisationSizeId' => (string)$organisation->getOrganisationSizeId(),

                'tags' => (new OrganisationTagRepository())->getTagsNameByOrganisationID($organisation->getId()),
                'members' => array_values(array_filter(array_map(function (User $user) use ($owner) {
                    if ($owner->getId() == $user->getId())
                        return null;
                    else
                        return [
                            'id' => $user->getId(),
                            'text' => $user->getFirstName() . ' ' . $user->getLastName(),
                            'firstName' => $user->getFirstName(),
                            'lastName' => $user->getLastName(),
                            'profilePic' => $user->getProfilePicOrDefault()
                        ];
                }, $organisationMembers))),
                'memberRequests' => array_map(function (User $user) {
                    return [
                        'id' => $user->getId(),
                        'text' => $user->getFirstName() . ' ' . $user->getLastName(),
                        'firstName' => $user->getFirstName(),
                        'lastName' => $user->getLastName(),
                        'profilePic' => $user->getProfilePicOrDefault()
                    ];
                }, $memberRequests),
                'organisationProjects' => array_map(function (OrganisationProject $organisationProject) {
                    $project = $organisationProject->getProject();
                    return [
                        'id' => $project->getId(),
                        'name' => $project->getName(),
                        'organisationsCount' => $project->getOrganisationsCount(),
                        'url' => URL::project($project),
                    ];
                }, $organisationProjects),
                'partnerOrganisations' => array_map(function (Organisation $organisation) {
                    return [
                        'id' => $organisation->getId(),
                        'name' => $organisation->getName(),
                        'commonProjects' => $organisation->extraData['common-projects'],
                        'url' => URL::organisation($organisation),
                    ];
                }, $partnerOrganisations),
                'countryID' => $organisation->getCountryID(),
                'countryRegionID' => $organisation->getCountryRegionID(),
                'countryRegion' => $organisation->getCountryRegion() ? $organisation->getCountryRegion()->getName() : '',
            ]);
            return;
        } else
        */
        $tags = array_map(function (OrganisationTag $organisationTag) {
            return $organisationTag->getTag();
        }, (new OrganisationTagRepository())->getByOrganisationID($organisation->getId()));

        $networkTags = array_map(function (OrganisationNetworkTag $organisationNetworkTag) {
            return $organisationNetworkTag->getTag();
        }, (new OrganisationNetworkTagRepository())->getByOrganisationID($organisation->getId()));

        $pageTitle = $organisation->getName();
        require __DIR__ . '/../../../www/views/organisation.php';

        return true;
    }

    /**
     * @return OrganisationController_Data
     */
    public function data()
    {
        return $this->data;
    }

    private function canUserRequestMembership(Organisation $organisation, User $loggedInUser)
    {
        if ($organisation->getOwnerID() == $loggedInUser->getId())
            return false;
        if ((new OrganisationMemberRepository())->organisationIDHasMemberID($organisation->getId(), $loggedInUser->getId()))
            return false;
        if ((new OrganisationMemberRequestRepository())->organisationHasRequestFromMember($organisation->getId(), $loggedInUser->getId()))
            return false;

        return true;
    }

    private function deleteOrganisation(User $loggedInUser, Organisation $organisation, URL $urlHandler)
    {
        $genSecureCode = new SecureCode();
        if ($genSecureCode->checkCode($_POST['secureCode'])) {
            try {
                $removeOrganisation = new RemoveOrganisation();
                $removeOrganisation->data()->executor = $loggedInUser;
                $removeOrganisation->data()->organisation = $organisation;
                $removeOrganisation->exec();

                echo json_encode([
                    'code' => 'ok',
                    'url' => $urlHandler->organisations()
                ]);
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors()
                ]);
            }
        }
        return true;
    }

    private function report(User $loggedInUser, Organisation $organisation, URL $urlHandler)
    {
        $genSecureCode = new SecureCode();
        if ($genSecureCode->checkCode($_POST['secureCode'])) {
            try {
                ob_start(); ?>
                User: <?php echo show_input($loggedInUser->getFullName()) ?>
                (<a href="https://<?php echo SITE_DOMAIN . $urlHandler->profile($loggedInUser) ?>">View profile</a>)
                <br/>
                Reported Organisation: <?php echo show_input($organisation->getName()) ?>
                (<a href="https://<?php echo SITE_DOMAIN . $urlHandler->organisation($organisation) ?>">View page</a>)
                <br/>
                Reason: <?php echo show_input($_POST['reason']) ?>
                <br/>
                <?php $message = ob_get_clean();

                $mail = new Mailer();
                $mail->Subject = 'Organisation Report on DSI4EU';
                $mail->wrapMessageInTemplate([
                    'header' => 'Organisation Report on DSI4EU',
                    'body' => $message
                ]);

                $exec = new SendEmailToCommunityAdmins();
                $exec->data()->executor = $loggedInUser;
                $exec->data()->mail = $mail;
                $exec->exec();

                echo json_encode([
                    'code' => 'ok',
                ]);
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors()
                ]);
            }
        }
        return true;
    }

    private function setSecureCode()
    {
        $genSecureCode = new SecureCode();
        $genSecureCode->exec();
        echo json_encode([
            'code' => 'ok',
            'secureCode' => $genSecureCode->getCode(),
        ]);

        return true;
    }

    private function joinOrganisation(User $user, Organisation $organisation)
    {
        $genSecureCode = new SecureCode();
        if ($genSecureCode->checkCode($_POST['secureCode'])) {
            try {
                $join = new AddMemberRequestToOrganisation();
                $join->data()->organisationID = $organisation->getId();
                $join->data()->userID = $user->getId();
                $join->exec();

                echo json_encode([
                    'code' => 'ok',
                ]);
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors()
                ]);
            }
        }
        return true;
    }

    /**
     * @param User $user
     * @param Organisation $organisation
     * @return bool
     */
    private function cancelJoinRequest(User $user, Organisation $organisation)
    {
        $genSecureCode = new SecureCode();
        if ($genSecureCode->checkCode($_POST['secureCode'])) {
            try {
                $exec = new RejectMemberRequestToOrganisation();
                $exec->data()->executor = $user;
                $exec->data()->organisationID = $organisation->getId();
                $exec->data()->userID = $user->getId();
                $exec->exec();

                echo json_encode([
                    'code' => 'ok',
                ]);
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors()
                ]);
            }
        }
        return true;
    }

    /**
     * @param User $user
     * @param Organisation $organisation
     * @return bool
     */
    private function leaveOrganisation(User $user, Organisation $organisation)
    {
        $genSecureCode = new SecureCode();
        if ($genSecureCode->checkCode($_POST['secureCode'])) {
            try {
                $exec = new RemoveMemberFromOrganisation();
                $exec->data()->organisationID = $organisation->getId();
                $exec->data()->userID = $user->getId();
                $exec->exec();

                echo json_encode([
                    'code' => 'ok',
                ]);
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors()
                ]);
            }
        }
        return true;
    }

    /**
     * @param User $user
     * @param Organisation $organisation
     * @return bool
     */
    private function followOrganisation(User $user, Organisation $organisation)
    {
        $genSecureCode = new SecureCode();
        if ($genSecureCode->checkCode($_POST['secureCode'])) {
            try {
                $exec = new FollowOrganisation();
                $exec->setOrganisation($organisation);
                $exec->setUser($user);
                $exec->setExecutor($user);
                $exec->exec();

                echo json_encode([
                    'code' => 'ok',
                ]);
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors()
                ]);
            }
        }
        return true;
    }

    /**
     * @param User $user
     * @param Organisation $organisation
     * @return bool
     */
    private function unfollowOrganisation(User $user, Organisation $organisation)
    {
        $genSecureCode = new SecureCode();
        if ($genSecureCode->checkCode($_POST['secureCode'])) {
            try {
                $exec = new UnfollowOrganisation();
                $exec->setOrganisation($organisation);
                $exec->setUser($user);
                $exec->setExecutor($user);
                $exec->exec();

                echo json_encode([
                    'code' => 'ok',
                ]);
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors()
                ]);
            }
        }
        return true;
    }

    /**
     * @param $organisation
     */
    private function updateOrganisationPartnersCount($organisation)
    {
        $exec = (new CalculateOrganisationPartnersCount());
        $exec->setOrganisation($organisation);
        $exec->exec();
    }
}

class OrganisationController_Data
{
    /** @var  int */
    public $organisationID;

    /** @var string */
    public $format = 'html';
}