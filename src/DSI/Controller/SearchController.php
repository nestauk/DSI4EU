<?php

namespace DSI\Controller;

use DSI\Entity\Organisation;
use DSI\Entity\Project;
use DSI\Entity\Story;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\StoryRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class SearchController
{
    /** @var string */
    public $term,
        $format = 'html';

    public function exec()
    {
        $authUser = new Auth();
        if ($authUser->isLoggedIn())
            $loggedInUser = (new UserRepository())->getById($authUser->getUserId());

        if ($this->format == 'json') {
            if (isset($_POST['term'])) {
                $organisations = (new OrganisationRepository())->searchByTitle($_POST['term'], 5);
                $projects = (new ProjectRepository())->searchByTitle($_POST['term'], 5);
                $stories = (new StoryRepository())->searchByTitle($_POST['term'], 5);
                echo json_encode([
                    'organisations' => array_map(function (Organisation $organisation) {
                        return [
                            'name' => $organisation->getName(),
                            'url' => URL::organisation($organisation->getId(), $organisation->getName()),
                        ];
                    }, $organisations),
                    'projects' => array_map(function (Project $project) {
                        return [
                            'name' => $project->getName(),
                            'url' => URL::project($project->getId(), $project->getName()),
                        ];
                    }, $projects),
                    'stories' => array_map(function (Story $story) {
                        return [
                            'name' => $story->getTitle(),
                            'url' => URL::story($story->getId(), $story->getTitle()),
                        ];
                    }, $stories),
                ]);
            }
        } else {
            $term = $this->term;
            $organisations = (new OrganisationRepository())->searchByTitle($this->term);
            $projects = (new ProjectRepository())->searchByTitle($this->term);
            $stories = (new StoryRepository())->searchByTitle($this->term);
            require __DIR__ . '/../../../www/search.php';
        }
    }
}