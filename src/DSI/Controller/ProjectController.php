<?php

namespace DSI\Controller;

use DSI\Repository\ProjectImpactTagARepository;
use DSI\Repository\ProjectImpactTagBRepository;
use DSI\Repository\ProjectImpactTagCRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\ProjectTagRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\AddImpactTagAToProject;
use DSI\UseCase\AddImpactTagBToProject;
use DSI\UseCase\AddImpactTagCToProject;
use DSI\UseCase\AddTagToProject;
use DSI\UseCase\RemoveImpactTagAFromProject;
use DSI\UseCase\RemoveImpactTagBFromProject;
use DSI\UseCase\RemoveImpactTagCFromProject;
use DSI\UseCase\RemoveTagFromProject;
use DSI\UseCase\UpdateProject;

class ProjectController
{
    /** @var  ProjectController_Data */
    private $data;

    public function __construct()
    {
        $this->data = new ProjectController_Data();
    }

    public function exec()
    {
        $loggedInUser = null;

        $authUser = new Auth();
        if ($authUser->isLoggedIn()) {
            $userRepo = new UserRepository();
            $loggedInUser = $userRepo->getById($authUser->getUserId());
        }

        $projectRepo = new ProjectRepository();
        $project = $projectRepo->getById($this->data()->projectID);

        try {
            if (isset($_POST['updateBasic'])) {
                $authUser->ifNotLoggedInRedirectTo(URL::login());

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
                echo json_encode(['result' => 'ok']);
                die();
            }

            if (isset($_POST['addTag'])) {
                $addTagToProject = new AddTagToProject();
                $addTagToProject->data()->projectID = $project->getId();
                $addTagToProject->data()->tag = $_POST['addTag'];
                $addTagToProject->exec();
                echo json_encode(['result' => 'ok']);
                die();
            }
            if (isset($_POST['removeTag'])) {
                $removeTagFromProject = new RemoveTagFromProject();
                $removeTagFromProject->data()->projectID = $project->getId();
                $removeTagFromProject->data()->tag = $_POST['removeTag'];
                $removeTagFromProject->exec();
                echo json_encode(['result' => 'ok']);
                die();
            }

            if (isset($_POST['addImpactTagA'])) {
                $addTagToProject = new AddImpactTagAToProject();
                $addTagToProject->data()->projectID = $project->getId();
                $addTagToProject->data()->tag = $_POST['addImpactTagA'];
                $addTagToProject->exec();
                echo json_encode(['result' => 'ok']);
                die();
            }
            if (isset($_POST['removeImpactTagA'])) {
                $removeTagFromProject = new RemoveImpactTagAFromProject();
                $removeTagFromProject->data()->projectID = $project->getId();
                $removeTagFromProject->data()->tag = $_POST['removeImpactTagA'];
                $removeTagFromProject->exec();
                echo json_encode(['result' => 'ok']);
                die();
            }

            if (isset($_POST['addImpactTagB'])) {
                $addTagToProject = new AddImpactTagBToProject();
                $addTagToProject->data()->projectID = $project->getId();
                $addTagToProject->data()->tag = $_POST['addImpactTagB'];
                $addTagToProject->exec();
                echo json_encode(['result' => 'ok']);
                die();
            }
            if (isset($_POST['removeImpactTagB'])) {
                $removeTagFromProject = new RemoveImpactTagBFromProject();
                $removeTagFromProject->data()->projectID = $project->getId();
                $removeTagFromProject->data()->tag = $_POST['removeImpactTagB'];
                $removeTagFromProject->exec();
                echo json_encode(['result' => 'ok']);
                die();
            }

            if (isset($_POST['addImpactTagC'])) {
                $addTagToProject = new AddImpactTagCToProject();
                $addTagToProject->data()->projectID = $project->getId();
                $addTagToProject->data()->tag = $_POST['addImpactTagC'];
                $addTagToProject->exec();
                echo json_encode(['result' => 'ok']);
                die();
            }
            if (isset($_POST['removeImpactTagC'])) {
                $removeTagFromProject = new RemoveImpactTagCFromProject();
                $removeTagFromProject->data()->projectID = $project->getId();
                $removeTagFromProject->data()->tag = $_POST['removeImpactTagC'];
                $removeTagFromProject->exec();
                echo json_encode(['result' => 'ok']);
                die();
            }

        } catch (ErrorHandler $e) {
            echo json_encode([
                'result' => 'error',
                'errors' => $e->getErrors()
            ]);
            die();
        }

        if ($this->data()->format == 'json') {
            echo json_encode([
                'name' => $project->getName(),
                'url' => $project->getUrl(),
                'status' => $project->getStatus(),
                'description' => $project->getDescription(),
                'startDate' => $project->getStartDate(),
                'endDate' => $project->getEndDate(),
                'tags' => (new ProjectTagRepository())->getTagsNameByProjectID($project->getId()),
                'impactTagsA' => (new ProjectImpactTagARepository())->getTagsNameByProjectID($project->getId()),
                'impactTagsB' => (new ProjectImpactTagBRepository())->getTagsNameByProjectID($project->getId()),
                'impactTagsC' => (new ProjectImpactTagCRepository())->getTagsNameByProjectID($project->getId()),
            ]);
            die();
        } else {
            require __DIR__ . '/../../../www/project.php';
        }
    }

    /**
     * @return ProjectController_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class ProjectController_Data
{
    /** @var  int */
    public $projectID;

    /** @var string */
    public $format = 'html';
}