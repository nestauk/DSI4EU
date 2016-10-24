<?php

namespace DSI\Controller;

use DSI\AccessDenied;
use DSI\Entity\Image;
use DSI\Entity\Project;
use DSI\Entity\ProjectLink_Service;
use DSI\Entity\User;
use DSI\Repository\DsiFocusTagRepository;
use DSI\Repository\ImpactTagRepository;
use DSI\Repository\OrganisationProjectRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\OrganisationRepositoryInAPC;
use DSI\Repository\ProjectImpactTagARepository;
use DSI\Repository\ProjectDsiFocusTagRepository;
use DSI\Repository\ProjectImpactTagCRepository;
use DSI\Repository\ProjectLinkRepository;
use DSI\Repository\ProjectMemberRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\ProjectTagRepository;
use DSI\Repository\TagForProjectsRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\JsModules;
use DSI\Service\URL;
use DSI\UseCase\UpdateProject;
use DSI\UseCase\UpdateProjectCountryRegion;
use DSI\UseCase\UpdateProjectLogo;

class ProjectEditController
{
    public $projectID;
    public $format = 'html';

    public function __construct()
    {
    }

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());
        $loggedInUser = $authUser->getUser();

        $projectRepo = new ProjectRepository();
        $project = $projectRepo->getById($this->projectID);

        if (!$this->userCanModifyProject($project, $loggedInUser))
            go_to($urlHandler->home());

        if ($this->format == 'json') {
            try {
                if (isset($_POST['saveDetails'])) {
                    if ($_POST['step'] == 'step1') {
                        $updateProject = new UpdateProject();
                        $updateProject->data()->project = $project;
                        $updateProject->data()->executor = $loggedInUser;
                        $updateProject->data()->name = $_POST['name'] ?? '';
                        $updateProject->data()->url = $_POST['url'] ?? '';
                        $updateProject->data()->tags = $_POST['tags'] ?? [];
                        $updateProject->data()->impactTagsA = $_POST['impactTagsA'] ?? [];
                        $updateProject->data()->impactTagsB = $_POST['impactTagsB'] ?? [];
                        $updateProject->data()->impactTagsC = $_POST['impactTagsC'] ?? [];
                        $updateProject->data()->links = $_POST['links'] ?? [];
                        $updateProject->data()->organisations = $_POST['organisations'] ?? [];
                        $updateProject->exec();
                    } elseif ($_POST['step'] == 'step2') {
                        $updateProject = new UpdateProject();
                        $updateProject->data()->project = $project;
                        $updateProject->data()->executor = $loggedInUser;
                        $updateProject->data()->startDate = $_POST['startDate'] ?? '';
                        $updateProject->data()->endDate = $_POST['endDate'] ?? '';
                        $updateProject->data()->countryID = $_POST['countryID'] ?? 0;
                        $updateProject->data()->region = $_POST['region'] ?? '';
                        $updateProject->exec();
                    } elseif ($_POST['step'] == 'step3') {
                        $updateProject = new UpdateProject();
                        $updateProject->data()->project = $project;
                        $updateProject->data()->executor = $loggedInUser;
                        $updateProject->data()->shortDescription = $_POST['shortDescription'] ?? '';
                        $updateProject->data()->description = $_POST['description'] ?? '';
                        $updateProject->data()->socialImpact = $_POST['socialImpact'] ?? '';
                        $updateProject->exec();
                    } elseif ($_POST['step'] == 'step4') {
                        $updateProject = new UpdateProject();
                        $updateProject->data()->project = $project;
                        $updateProject->data()->executor = $loggedInUser;
                        $updateProject->data()->logo = $_POST['logo'] ?? '';
                        $updateProject->data()->headerImage = $_POST['headerImage'] ?? '';
                        $updateProject->exec();
                    }

                    echo json_encode(['code' => 'ok']);
                    return;
                }
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors()
                ]);
                return;
            }

            $owner = $project->getOwner();
            $links = [];
            $projectLinks = (new ProjectLinkRepository())->getByProjectID($project->getId());
            foreach ($projectLinks AS $projectLink) {
                if ($projectLink->getLinkService() == ProjectLink_Service::Facebook)
                    $links['facebook'] = $projectLink->getLink();
                if ($projectLink->getLinkService() == ProjectLink_Service::Twitter)
                    $links['twitter'] = $projectLink->getLink();
                if ($projectLink->getLinkService() == ProjectLink_Service::GooglePlus)
                    $links['googleplus'] = $projectLink->getLink();
                if ($projectLink->getLinkService() == ProjectLink_Service::GitHub)
                    $links['github'] = $projectLink->getLink();
            }

            echo json_encode([
                'name' => $project->getName(),
                'url' => $project->getUrl(),
                'status' => $project->getStatus(),
                'shortDescription' => $project->getShortDescription(),
                'description' => $project->getDescription(),
                'startDate' => $project->getStartDate(),
                //'startDateHumanReadable' => $project->getUnixStartDate() ? date('l, j F, Y', $project->getUnixStartDate()) : '',
                'endDate' => $project->getEndDate(),
                //'endDateHumanReadable' => $project->getUnixEndDate() ? date('l, j F, Y', $project->getUnixEndDate()) : '',
                'countryID' => $project->getCountryID(),
                'region' => $project->getRegionName(),
                'logo' => $project->getLogo() ?
                    Image::PROJECT_LOGO_URL . $project->getLogo() : '',
                'headerImage' => $project->getHeaderImage() ?
                    Image::PROJECT_HEADER_URL . $project->getHeaderImage() : '',
                'links' => $links ? $links : '',
            ]);
            return;

        } else {
            $data = ['project' => $project];
            $tags = (new TagForProjectsRepository())->getAll();
            $impactTags = (new ImpactTagRepository())->getAll();
            $dsiFocusTags = (new DsiFocusTagRepository())->getAll();
            $projectImpactTagsA = (new ProjectImpactTagARepository())->getTagsNameByProjectID($project->getId());
            $projectImpactTagsB = (new ProjectDsiFocusTagRepository())->getTagsNameByProjectID($project->getId());
            $projectImpactTagsC = (new ProjectImpactTagCRepository())->getTagsNameByProjectID($project->getId());
            $projectTags = (new ProjectTagRepository())->getTagsNameByProjectID($project->getId());
            $organisations = (new OrganisationRepositoryInAPC())->getAll();
            $projectOrganisations = (new OrganisationProjectRepository())->getOrganisationIDsForProject($project->getId());
            $angularModules['fileUpload'] = true;
            JsModules::setTinyMCE(true);
            require __DIR__ . '/../../../www/views/project-edit.php';
        }
    }

    private function userCanModifyProject(Project $project, User $user)
    {
        if ($project->getOwnerID() == $user->getId())
            return true;

        if ((new ProjectMemberRepository())->projectHasMember($project->getId(), $user->getId()))
            return true;

        if ($user->isCommunityAdmin())
            return true;

        return false;
    }
}