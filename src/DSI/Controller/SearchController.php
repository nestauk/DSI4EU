<?php

namespace DSI\Controller;

use DSI\Entity\CaseStudy;
use DSI\Entity\Organisation;
use DSI\Entity\Project;
use DSI\Entity\Story;
use DSI\Repository\CaseStudyRepository;
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
                $blogPosts = (new StoryRepository())->searchByTitle($_POST['term'], 5);
                $caseStudies = (new CaseStudyRepository())->searchByTitle($_POST['term'], 5);
                echo json_encode([
                    'organisations' => array_map(function (Organisation $organisation) {
                        return [
                            'name' => $organisation->getName(),
                            'url' => URL::organisation($organisation),
                        ];
                    }, $organisations),
                    'caseStudies' => array_map(function (CaseStudy $caseStudy) {
                        return [
                            'name' => $caseStudy->getTitle(),
                            'url' => URL::caseStudy($caseStudy),
                        ];
                    }, $caseStudies),
                    'projects' => array_map(function (Project $project) {
                        return [
                            'name' => $project->getName(),
                            'url' => URL::project($project),
                        ];
                    }, $projects),
                    'blogPosts' => array_map(function (Story $story) {
                        return [
                            'name' => $story->getTitle(),
                            'url' => URL::story($story->getId(), $story->getTitle()),
                        ];
                    }, $blogPosts),
                ]);
            }
        } else {
            $term = $this->term;
            $caseStudies = (new CaseStudyRepository())->searchByTitle($this->term);
            $organisations = (new OrganisationRepository())->searchByTitle($this->term);
            $projects = (new ProjectRepository())->searchByTitle($this->term);
            $blogPosts = (new StoryRepository())->searchByTitle($this->term);
            require __DIR__ . '/../../../www/search.php';
        }
    }
}