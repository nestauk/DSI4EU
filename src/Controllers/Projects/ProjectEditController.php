<?php

namespace Controllers\Projects;

use DSI\Entity\Image;
use DSI\Entity\ImpactTag;
use DSI\Entity\Project;
use DSI\Entity\ProjectLink_Service;
use DSI\Entity\User;
use DSI\Repository\DsiFocusTagRepo;
use DSI\Repository\ImpactTagRepo;
use DSI\Repository\OrganisationProjectRepo;
use DSI\Repository\OrganisationRepoInAPC;
use DSI\Repository\ProjectImpactHelpTagRepo;
use DSI\Repository\ProjectDsiFocusTagRepo;
use DSI\Repository\ProjectImpactTechTagRepo;
use DSI\Repository\ProjectLinkRepo;
use DSI\Repository\ProjectMemberRepo;
use DSI\Repository\ProjectRepoInAPC;
use DSI\Repository\ProjectTagRepo;
use DSI\Repository\TagForProjectsRepo;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\JsModules;
use Services\URL;
use DSI\UseCase\UpdateProject;
use Services\View;

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

        $projectRepo = new ProjectRepoInAPC();
        $project = $projectRepo->getById($this->projectID);

        if (!$this->userCanModifyProject($project, $loggedInUser))
            go_to($urlHandler->home());

        if ($this->format == 'json') {
            try {
                if (isset($_POST['saveDetails'])) {
                    if ($_POST['step'] == '1') {
                        $updateProject = new UpdateProject();
                        $updateProject->data()->project = $project;
                        $updateProject->data()->executor = $loggedInUser;
                        $updateProject->data()->name = $_POST['name'] ?? '';
                        $updateProject->data()->url = $_POST['url'] ?? '';
                        $updateProject->data()->tags = $_POST['tags'] ?? [];
                        $updateProject->data()->areasOfImpact = $_POST['areasOfImpact'] ?? [];
                        $updateProject->data()->focusTags = $_POST['focusTags'] ?? [];
                        $updateProject->data()->technologyTags = $_POST['impactTagsC'] ?? [];
                        $updateProject->data()->links = $_POST['links'] ?? [];
                        $updateProject->data()->organisations = $_POST['organisations'] ?? [];
                        $updateProject->exec();
                    } elseif ($_POST['step'] == '2') {
                        $updateProject = new UpdateProject();
                        $updateProject->data()->project = $project;
                        $updateProject->data()->executor = $loggedInUser;
                        $updateProject->data()->startDate = $_POST['startDate'] ?? '';
                        $updateProject->data()->endDate = $_POST['endDate'] ?? '';
                        $updateProject->data()->countryID = $_POST['countryID'] ?? 0;
                        $updateProject->data()->region = $_POST['region'] ?? '';
                        $updateProject->exec();
                    } elseif ($_POST['step'] == '3') {
                        $updateProject = new UpdateProject();
                        $updateProject->data()->project = $project;
                        $updateProject->data()->executor = $loggedInUser;
                        $updateProject->data()->shortDescription = $_POST['shortDescription'] ?? '';
                        $updateProject->data()->description = $_POST['description'] ?? '';
                        $updateProject->data()->socialImpact = $_POST['socialImpact'] ?? '';
                        $updateProject->exec();
                    } elseif ($_POST['step'] == '4') {
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
            $projectLinks = (new ProjectLinkRepo())->getByProjectID($project->getId());
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
            $impactTags = (new ImpactTagRepo())->getAll();
            $technologyTags = (new ImpactTagRepo())->orderByTechnology()->getAll();

            $angularModules['fileUpload'] = true;
            JsModules::setTinyMCE(true);
            View::render(__DIR__ . '/../../Views/project/project-edit.php', [
                'loggedInUser' => $loggedInUser,

                'project' => $project,
                'tags' => (new TagForProjectsRepo())->getAll(),

                'impactTags' => $impactTags,
                'impactMainTags' => array_filter($impactTags, function (ImpactTag $impactTag) {
                    return $impactTag->isMain();
                }),
                'impactSecondaryTags' => array_filter($impactTags, function (ImpactTag $impactTag) {
                    return !$impactTag->isMain();
                }),
                'technologyTags' => $technologyTags,
                'technologyMainTags' => collect($technologyTags)
                    ->filter(function (ImpactTag $impactTag) {
                        return $impactTag->isTechnologyMain();
                    })
                    ->sortBy(function(ImpactTag $impactTag){
                        return $impactTag->getName();
                    }),
                'technologySecondaryTags' => array_filter($technologyTags, function (ImpactTag $impactTag) {
                    return !$impactTag->isTechnologyMain();
                }),

                'dsiFocusTags' => (new DsiFocusTagRepo())->getAll(),
                'projectImpactTagsA' => (new ProjectImpactHelpTagRepo())->getTagNamesByProject($project),
                'projectImpactTagsB' => (new ProjectDsiFocusTagRepo())->getTagNamesByProject($project),
                'projectImpactTagsC' => (new ProjectImpactTechTagRepo())->getTagNamesByProject($project),
                'projectTags' => (new ProjectTagRepo())->getTagNamesByProject($project),
                'organisations' => (new OrganisationRepoInAPC())->getAll(),
                'projectOrganisations' => (new OrganisationProjectRepo())->getOrganisationIDsForProject($project),
                'angularModules' => [
                    'fileUpload' => true
                ]
            ]);
        }
    }

    private function userCanModifyProject(Project $project, User $user)
    {
        if ($project->getOwnerID() == $user->getId())
            return true;

        if ((new ProjectMemberRepo())->projectHasMember($project, $user))
            return true;

        if ($user->isCommunityAdmin())
            return true;

        return false;
    }
}