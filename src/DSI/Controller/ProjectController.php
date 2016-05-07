<?php

namespace DSI\Controller;

use DSI\Repository\ProjectRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
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

        if ($_POST['updateBasic']) {
            $authUser->ifNotLoggedInRedirectTo(URL::login());

            $updateProject = new UpdateProject();
            $updateProject->data()->project = $project;
            $updateProject->data()->user = $loggedInUser;
            if (isset($_POST['name']))
                $updateProject->data()->name = $_POST['name'];
            if (isset($_POST['url']))
                $updateProject->data()->url = $_POST['url'];

            try {
                $updateProject->exec();
                echo json_encode([
                    'result' => 'ok',
                ]);
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'result' => 'error',
                    'errors' => $e->getErrors(),
                ]);
            }
            die();
        }

        if ($this->data()->format == 'json') {
            echo json_encode([
                'name' => $project->getName(),
                'url' => $project->getUrl(),
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