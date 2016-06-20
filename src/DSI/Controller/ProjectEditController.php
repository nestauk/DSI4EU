<?php

namespace DSI\Controller;

use DSI\Entity\Image;
use DSI\Entity\OrganisationProject;
use DSI\Entity\Project;
use DSI\Entity\ProjectMember;
use DSI\Entity\ProjectPost;
use DSI\Entity\User;
use DSI\Repository\OrganisationProjectRepository;
use DSI\Repository\ProjectImpactTagARepository;
use DSI\Repository\ProjectImpactTagBRepository;
use DSI\Repository\ProjectImpactTagCRepository;
use DSI\Repository\ProjectMemberInvitationRepository;
use DSI\Repository\ProjectMemberRepository;
use DSI\Repository\ProjectMemberRequestRepository;
use DSI\Repository\ProjectPostRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\ProjectTagRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\AddEmailToProject;
use DSI\UseCase\AddImpactTagAToProject;
use DSI\UseCase\AddImpactTagBToProject;
use DSI\UseCase\AddImpactTagCToProject;
use DSI\UseCase\AddMemberInvitationToProject;
use DSI\UseCase\AddMemberRequestToProject;
use DSI\UseCase\AddProjectToOrganisation;
use DSI\UseCase\AddTagToProject;
use DSI\UseCase\ApproveMemberRequestToProject;
use DSI\UseCase\CreateProjectPost;
use DSI\UseCase\RejectMemberRequestToProject;
use DSI\UseCase\RemoveImpactTagAFromProject;
use DSI\UseCase\RemoveImpactTagBFromProject;
use DSI\UseCase\RemoveImpactTagCFromProject;
use DSI\UseCase\RemoveMemberFromProject;
use DSI\UseCase\RemoveTagFromProject;
use DSI\UseCase\SetAdminStatusToProjectMember;
use DSI\UseCase\UpdateProject;
use DSI\UseCase\UpdateProjectCountryRegion;
use DSI\UseCase\UpdateProjectLogo;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class ProjectEditController
{
    public $projectID;
    public $format = 'html';

    public function __construct()
    {
    }

    public function exec()
    {
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo(URL::login());
        $loggedInUser = (new UserRepository())->getById($authUser->getUserId());

        $projectRepo = new ProjectRepository();
        $project = $projectRepo->getById($this->projectID);

        if ($project->getOwner()->getId() != $loggedInUser->getId())
            throw new AccessDeniedException('You cannot access this page');

        if ($this->format == 'json') {
            try {
                if (isset($_POST['saveDetails'])) {
                    $updateProject = new UpdateProject();
                    $updateProject->data()->project = $project;
                    $updateProject->data()->user = $loggedInUser;
                    if (isset($_POST['name']))
                        $updateProject->data()->name = $_POST['name'];
                    if (isset($_POST['url']))
                        $updateProject->data()->url = $_POST['url'];
                    if (isset($_POST['status']))
                        $updateProject->data()->status = $_POST['status'];
                    if (isset($_POST['description']))
                        $updateProject->data()->description = $_POST['description'];

                    $updateProject->data()->startDate = $_POST['startDate'] ?? NULL;
                    $updateProject->data()->endDate = $_POST['endDate'] ?? NULL;
                    $updateProject->exec();

                    $updateProjectCountryRegionCmd = new UpdateProjectCountryRegion();
                    $updateProjectCountryRegionCmd->data()->projectID = $project->getId();
                    $updateProjectCountryRegionCmd->data()->countryID = $_POST['countryID'] ?? '';
                    $updateProjectCountryRegionCmd->data()->region = $_POST['region'] ?? '';
                    $updateProjectCountryRegionCmd->exec();

                    if ($_POST['logo'] != Image::PROJECT_LOGO_URL . $project->getLogoOrDefault()) {
                        $updateProjectLogo = new UpdateProjectLogo();
                        $updateProjectLogo->data()->projectID = $project->getId();
                        $updateProjectLogo->data()->fileName = basename($_POST['logo']);
                        $updateProjectLogo->exec();
                    }

                    echo json_encode([
                        'result' => 'ok',
                        'message' => [
                            'title' => 'Success',
                            'text' => 'Project Details have been successfully saved',
                        ],
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

            $owner = $project->getOwner();
            echo json_encode([
                'name' => $project->getName(),
                'url' => $project->getUrl(),
                'status' => $project->getStatus(),
                'description' => $project->getDescription(),
                'startDate' => $project->getStartDate(),
                'startDateHumanReadable' => $project->getUnixStartDate() ? date('l, j F, Y', $project->getUnixStartDate()) : '',
                'endDate' => $project->getEndDate(),
                'endDateHumanReadable' => $project->getUnixEndDate() ? date('l, j F, Y', $project->getUnixEndDate()) : '',
                'countryID' => $project->getCountryID(),
                'countryRegionID' => $project->getCountryRegionID(),
                'countryRegion' => $project->getCountryRegion() ? $project->getCountryRegion()->getName() : '',
                'logo' => Image::PROJECT_LOGO_URL . $project->getLogoOrDefault(),
            ]);
            return;

        } else {
            $data = ['project' => $project];
            $angularModules['fileUpload'] = true;
            require __DIR__ . '/../../../www/project-edit.php';
        }
    }
}