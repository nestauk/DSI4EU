<?php

namespace DSI\Controller;

use DSI\Entity\CaseStudy;
use DSI\Entity\Organisation;
use DSI\Entity\Project;
use DSI\Entity\Story;
use DSI\Repository\CaseStudyRepo;
use DSI\Repository\OrganisationRepo;
use DSI\Repository\ProjectRepo;
use DSI\Repository\StoryRepo;
use DSI\Repository\UserRepo;
use DSI\Service\Auth;
use Services\URL;

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
                $organisations = (new OrganisationRepo())->searchByTitle($_POST['term'], 5);
                $projects = (new ProjectRepo())->searchByTitle($_POST['term'], 5);
                $blogPosts = (new StoryRepo())->searchByTitle($_POST['term'], 5);
                $caseStudies = (new CaseStudyRepo())->searchByTitle($_POST['term'], 5);
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
            $caseStudies = (new CaseStudyRepo())->searchByTitle($this->term);
            $organisations = (new OrganisationRepo())->searchByTitle($this->term);
            $projects = (new ProjectRepo())->searchByTitle($this->term);
            $blogPosts = (new StoryRepo())->searchByTitle($this->term);
            require __DIR__ . '/../../../www/views/search.php';
        }
    }
}