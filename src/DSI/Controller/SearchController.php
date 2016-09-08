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
        $urlHandler = new URL();
        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();

        if ($this->format == 'json') {
            if (isset($_POST['term'])) {
                $organisations = (new OrganisationRepository())->searchByTitle($_POST['term'], 5);
                $projects = (new ProjectRepository())->searchByTitle($_POST['term'], 5);
                $blogPosts = (new StoryRepository())->searchByTitle($_POST['term'], 5);
                $caseStudies = (new CaseStudyRepository())->searchByTitle($_POST['term'], 5);
                echo json_encode([
                    'organisations' => array_map(function (Organisation $organisation) use ($urlHandler) {
                        return [
                            'name' => $organisation->getName(),
                            'url' => $urlHandler->organisation($organisation),
                        ];
                    }, $organisations),
                    'caseStudies' => array_map(function (CaseStudy $caseStudy) use ($urlHandler){
                        return [
                            'name' => $caseStudy->getTitle(),
                            'url' => $urlHandler->caseStudy($caseStudy),
                        ];
                    }, $caseStudies),
                    'projects' => array_map(function (Project $project) use ($urlHandler) {
                        return [
                            'name' => $project->getName(),
                            'url' => $urlHandler->project($project),
                        ];
                    }, $projects),
                    'blogPosts' => array_map(function (Story $story) use ($urlHandler){
                        return [
                            'name' => $story->getTitle(),
                            'url' => $urlHandler->blogPost($story),
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