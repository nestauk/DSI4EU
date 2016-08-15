<?php

namespace DSI\Controller;

use DSI\Repository\CaseStudyRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\OrganisationRepositoryInAPC;
use DSI\Repository\ProjectRepository;
use DSI\Repository\ProjectRepositoryInAPC;
use DSI\Repository\StoryRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class SitemapController
{
    public $format = 'html';

    /** @var URL */
    private $urlHandler;

    public function exec()
    {
        $this->urlHandler = new URL();
        $authUser = new Auth();
        if ($authUser->isLoggedIn())
            $loggedInUser = (new UserRepository())->getById($authUser->getUserId());

        if ($this->format == 'xml') {
            header("Content-type: text/xml");
            
            $links = [];
            $links = $this->addIndexTo($links);
            $links = $this->addProjectsTo($links);
            $links = $this->addOrganisationsTo($links);
            $links = $this->addStoriesTo($links);
            $links = $this->addCaseStudiesTo($links);

            require __DIR__ . '/../../../www/sitemap.xml.php';
        }
    }

    /**
     * @param $links
     * @return array
     */
    private function addOrganisationsTo($links)
    {
        $organisations = (new OrganisationRepositoryInAPC())->getAll();
        // $organisations = (new OrganisationRepository())->getAllPublished();
        foreach ($organisations AS $organisation) {
            $organisationLink = new SitemapLink();
            $organisationLink->loc = 'https://' . SITE_DOMAIN . $this->urlHandler->organisation($organisation);
            $organisationLink->lastMod = date('Y-m-01');
            $organisationLink->changeFreq = 'monthly';
            $organisationLink->priority = '0.9';
            $links[] = $organisationLink;
        }
        return $links;
    }

    /**
     * @param $links
     * @return array
     */
    private function addStoriesTo($links)
    {
        $stories = (new StoryRepository())->getAllPublished();
        foreach ($stories AS $story) {
            $storyLink = new SitemapLink();
            $storyLink->loc = 'https://' . SITE_DOMAIN . $this->urlHandler->blogPost($story);
            $storyLink->lastMod = date('Y-m-01');
            $storyLink->changeFreq = 'monthly';
            $storyLink->priority = '0.9';
            $links[] = $storyLink;
        }
        return $links;
    }

    /**
     * @param $links
     * @return array
     */
    private function addCaseStudiesTo($links)
    {
        $caseStudies = (new CaseStudyRepository())->getAllPublished();
        foreach ($caseStudies AS $caseStudy) {
            $link = new SitemapLink();
            $link->loc = 'https://' . SITE_DOMAIN . $this->urlHandler->caseStudy($caseStudy);
            $link->lastMod = date('Y-m-01');
            $link->changeFreq = 'monthly';
            $link->priority = '0.9';
            $links[] = $link;
        }
        return $links;
    }

    /**
     * @param $links
     * @return array
     */
    private function addProjectsTo($links)
    {
        $projects = (new ProjectRepositoryInAPC())->getAll();
        // $projects = (new ProjectRepository())->getAllPublished();
        foreach ($projects AS $project) {
            $projectLink = new SitemapLink();
            $projectLink->loc = 'https://' . SITE_DOMAIN . $this->urlHandler->project($project);
            $projectLink->lastMod = date('Y-m-01');
            $projectLink->changeFreq = 'monthly';
            $projectLink->priority = '0.9';
            $links[] = $projectLink;
        }
        return $links;
    }

    /**
     * @param $links
     * @return array
     */
    private function addIndexTo($links)
    {
        $indexLink = new SitemapLink();
        $indexLink->loc = 'https://' . SITE_DOMAIN;
        $indexLink->lastMod = date('Y-m-01');
        $indexLink->changeFreq = 'monthly';
        $indexLink->priority = '1.0';
        $links[] = $indexLink;
        return $links;
    }
}

class SitemapLink
{
    /** @var string */
    public $loc,
        $lastMod,
        $changeFreq,
        $priority;
}