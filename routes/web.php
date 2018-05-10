<?php

use \DSI\Service\Translate;
use \DSI\Entity\Translation;

class Router
{
    /** @var string */
    private $pageURL;

    public function exec(string $pageURL)
    {
        Translate::setCurrentLang(Translation::DEFAULT_LANGUAGE);
        $this->pageURL = $pageURL;

        $langHandler = '(([a-z]{2})/?)?';

        if (preg_match('<^/' . $langHandler . '$>', $this->pageURL, $matches)) {
            $this->homePage($matches);

        } elseif (preg_match('<^/' . $langHandler . 'dashboard$>', $this->pageURL, $matches)) {
            $this->dashboard($matches);

        } elseif (preg_match('<^/' . $langHandler . 'dashboard\.json$>', $this->pageURL, $matches)) {
            $this->dashboardJson($matches);

        } elseif (preg_match('<^/' . $langHandler . 'notifications\.json$>', $this->pageURL, $matches)) {
            $this->notificationsJson($matches);

        } elseif ($this->pageURL === '/forgotPassword.json') {
            $this->forgotPasswordJson();

        } elseif ($this->pageURL === '/facebook-login') {
            $this->facebookLogin();

        } elseif ($this->pageURL === '/google-login') {
            $this->googleLogin();

        } elseif ($this->pageURL === '/github-login') {
            $this->gitHubLogin();

        } elseif ($this->pageURL === '/twitter-login') {
            $this->twitterLogin();

        } elseif ($this->pageURL === '/set-admin') {
            $this->setAdmin();

        } elseif ($this->pageURL === '/register.json') {
            $this->registerJson();

        } elseif (preg_match('<^/' . $langHandler . 'register>', $this->pageURL, $matches)) {
            $this->register($matches);

        } elseif (preg_match('<^/' . $langHandler . 'login$>', $this->pageURL, $matches)) {
            $this->login($matches);

        } elseif (preg_match('<^/' . $langHandler . 'login\.json$>', $this->pageURL, $matches)) {
            $this->login($matches, 'json');

        } elseif (preg_match('<^/' . $langHandler . 'logout$>', $this->pageURL, $matches)) {
            $this->logout($matches);

// Funding
        } elseif (preg_match('<^/' . $langHandler . 'funding$>', $this->pageURL, $matches)) {
            $this->funding($matches);

        } elseif (preg_match('<^/' . $langHandler . 'funding\.json$>', $this->pageURL, $matches)) {
            $this->funding($matches, 'json');

        } elseif (preg_match('<^/' . $langHandler . 'funding/add$>', $this->pageURL, $matches)) {
            $this->addFunding($matches);

        } elseif (preg_match('<^/' . $langHandler . 'funding/edit/([0-9]+)$>', $this->pageURL, $matches)) {
            $this->editFunding($matches);

        } elseif (preg_match('<^/' . $langHandler . 'funding/edit/([0-9]+)\.json$>', $this->pageURL, $matches)) {
            $this->editFunding($matches, 'json');

// Events
        } elseif (preg_match('<^/' . $langHandler . 'events$>', $this->pageURL, $matches)) {
            $this->events($matches);

        } elseif (preg_match('<^/' . $langHandler . 'events\.json$>', $this->pageURL, $matches)) {
            $this->events($matches, 'json');

        } elseif (preg_match('<^/' . $langHandler . 'events/add$>', $this->pageURL, $matches)) {
            $this->addEvent($matches);

        } elseif (preg_match('<^/' . $langHandler . 'event/edit/([0-9]+)$>', $this->pageURL, $matches)) {
            $this->editEvent($matches);

        } elseif (preg_match('<^/' . $langHandler . 'event/edit/([0-9]+)\.json$>', $this->pageURL, $matches)) {
            $this->editEvent($matches, 'json');

        } elseif (preg_match('<^/' . $langHandler . 'event/([0-9]+)$>', $this->pageURL, $matches)) {
            $this->event($matches);

// Projects
        } elseif (preg_match('<^/' . $langHandler . 'projects$>', $this->pageURL, $matches)) {
            $this->projects($matches);

        } elseif (preg_match('<^/' . $langHandler . 'projects\.json$>', $this->pageURL, $matches)) {
            $this->projectsJson($matches);

        } elseif (preg_match('<^/' . $langHandler . 'projectTags\.json$>', $this->pageURL, $matches)) {
            $this->projectTagsJson($matches);

        } elseif (preg_match('<^/' . $langHandler . 'createProject\.json$>', $this->pageURL, $matches)) {
            $this->createProject($matches);

// Organisations
        } elseif (preg_match('<^/' . $langHandler . 'organisationTags\.json$>', $this->pageURL, $matches)) {
            $this->organisationTagsJson($matches);

        } elseif (preg_match('<^/' . $langHandler . 'organisations$>', $this->pageURL, $matches)) {
            $this->organisations($matches);

        } elseif (preg_match('<^/' . $langHandler . 'organisations.json$>', $this->pageURL, $matches)) {
            $this->organisationsJson($matches);

        } elseif (preg_match('<^/' . $langHandler . 'createOrganisation.json$>', $this->pageURL, $matches)) {
            $this->createOrganisation($matches);

        } elseif ($this->pageURL === '/uploadProfilePicture') {
            $this->uploadProfilePicture();

        } elseif (preg_match('<^/' . $langHandler . 'personal-details$>', $this->pageURL, $matches)) {
            $this->profileEdit($matches);

        } elseif (preg_match('<^/' . $langHandler . 'profile/edit/([0-9]+)$>', $this->pageURL, $matches)) {
            $this->profileEdit($matches);

        } elseif (preg_match('<^/' . $langHandler . 'profile/editPrivileges/([0-9]+)$>', $this->pageURL, $matches)) {
            $this->profileEditPrivileges($matches);

        } elseif (preg_match('<^/' . $langHandler . 'profile/edit/([0-9]+)\.json$>', $this->pageURL, $matches)) {
            $this->profileEdit($matches, 'json');

        } elseif ($this->pageURL === '/skills.json') {
            $this->skillsListJson();

        } elseif ($this->pageURL === '/users.json') {
            $this->usersListJson();

        } elseif ($this->pageURL === '/tags-for-projects.json') {
            $this->tagsForProjectsListJson();

        } elseif ($this->pageURL === '/tags-for-organisations.json') {
            $this->tagsForOrganisationsListJson();

        } elseif ($this->pageURL === '/impact-tags.json') {
            $this->impactTagsListJson();

        } elseif ($this->pageURL === '/languages.json') {
            $this->languagesListJson();

        } elseif ($this->pageURL === '/countries.json') {
            $this->countriesListJson();

        } elseif ($this->pageURL === '/organisations.json') {
            $this->organisationsListJson();

        } elseif (preg_match('<^/' . $langHandler . 'feedback\.json$>', $this->pageURL, $matches)) {
            $this->feedbackJson($matches);

        } elseif (preg_match('<^/' . $langHandler . 'feedback$>', $this->pageURL, $matches)) {
            $this->feedback($matches);

        } elseif ($this->pageURL === '/temp-gallery.json') {
            $this->tempGalleryJson();

        } elseif ($this->pageURL === '/uploadImage.json') {
            $this->uploadImageJson();

// Blog
        } elseif (preg_match('<^/' . $langHandler . 'stories$>', $this->pageURL, $matches)) {
            $this->stories($matches);
        } elseif (preg_match('<^/' . $langHandler . 'blog$>', $this->pageURL, $matches)) {
            $this->stories($matches);

        } elseif (preg_match('<^/' . $langHandler . 'stories\.json$>', $this->pageURL, $matches)) {
            $this->storiesJson($matches);
        } elseif (preg_match('<^/' . $langHandler . 'blog\.json$>', $this->pageURL, $matches)) {
            $this->storiesJson($matches);

        } elseif (preg_match('<^/' . $langHandler . 'story/add$>', $this->pageURL, $matches)) {
            $this->addStory($matches);

        } elseif (preg_match('<^/' . $langHandler . 'blog/add$>', $this->pageURL, $matches)) {
            $this->addStory($matches);

        } elseif (preg_match('<^/' . $langHandler . 'story/([0-9]+)(\/.*)?$>', $this->pageURL, $matches)) {
            $this->story($matches);
        } elseif (preg_match('<^/' . $langHandler . 'blog/([0-9]+)(\/.*)?$>', $this->pageURL, $matches)) {
            $this->story($matches);

        } elseif (preg_match('<^/' . $langHandler . 'story/edit/([0-9]+)\.json$>', $this->pageURL, $matches)) {
            $this->editStory($matches, 'json');
        } elseif (preg_match('<^/' . $langHandler . 'blog/edit/([0-9]+)\.json$>', $this->pageURL, $matches)) {
            $this->editStory($matches, 'json');

        } elseif (preg_match('<^/' . $langHandler . 'story/edit/([0-9]+)$>', $this->pageURL, $matches)) {
            $this->editStory($matches);
        } elseif (preg_match('<^/' . $langHandler . 'blog/edit/([0-9]+)$>', $this->pageURL, $matches)) {
            $this->editStory($matches);

// Search
        } elseif (preg_match('<^/' . $langHandler . 'search\.json$>', $this->pageURL, $matches)) {
            $this->search($matches, 'json');

        } elseif (preg_match('<^/' . $langHandler . 'search/(.*)$>', $this->pageURL, $matches)) {
            $this->search($matches);

// Case Studies
        } elseif (preg_match('<^/' . $langHandler . 'case-study/add$>', $this->pageURL, $matches)) {
            $this->addCaseStudy($matches);

        } elseif (preg_match('<^/' . $langHandler . 'case-studies$>', $this->pageURL, $matches)) {
            $this->caseStudies($matches);

        } elseif (preg_match('<^/' . $langHandler . 'case-studies\.json$>', $this->pageURL, $matches)) {
            $this->caseStudiesJson($matches);

        } elseif (preg_match('<^/' . $langHandler . 'case-study/edit/([0-9]+)\.json$>', $this->pageURL, $matches)) {
            $this->editCaseStudy($matches, 'json');

        } elseif (preg_match('<^/' . $langHandler . 'case-study/edit/([0-9]+)$>', $this->pageURL, $matches)) {
            $this->editCaseStudy($matches);

        } elseif (preg_match('<^/' . $langHandler . 'case-study/([0-9]+)(\/.*)?$>', $this->pageURL, $matches)) {
            $this->caseStudy($matches);

// Organisations
        } elseif (preg_match('<^/' . $langHandler . 'org/edit/([0-9]+)\.json$>', $this->pageURL, $matches)) {
            $this->editOrganisation($matches, 'json');

        } elseif (preg_match('<^/' . $langHandler . 'org/edit/([0-9]+)$>', $this->pageURL, $matches)) {
            $this->editOrganisation($matches);

        } elseif (preg_match('<^/' . $langHandler . 'org/editOwner/([0-9]+)$>', $this->pageURL, $matches)) {
            $this->editOrganisationOwner($matches);

        } elseif (preg_match('<^/' . $langHandler . 'org/members/([0-9]+)$>', $this->pageURL, $matches)) {
            $this->editOrganisationsMembers($matches);

        } elseif (preg_match('<^/' . $langHandler . 'org/members/([0-9]+)\.json$>', $this->pageURL, $matches)) {
            $this->editOrganisationsMembers($matches, 'json');

        } elseif (preg_match('<^/' . $langHandler . 'org/([0-9]+)(\/.*)?$>', $this->pageURL, $matches)) {
            $this->organisation($matches);

// RSS feeds
        } elseif ($this->pageURL === '/rss/news-and-blogs.xml') {
            $this->rssNewsBlogs();

        } elseif ($this->pageURL === '/rss/events.xml') {
            $this->rssEvents();

        } elseif ($this->pageURL === '/rss/funding-opportunities.xml') {
            $this->rssFundingOpportunities();

// Static pages
        } elseif ($this->pageURL === '/robots.txt') {
            $this->robotsTxt();

        } elseif (preg_match('<^/' . $langHandler . 'explore-dsi$>', $this->pageURL, $matches)) {
            $this->staticPage($matches, 'explore-dsi.php');

        } elseif (preg_match('<^/' . $langHandler . 'terms-of-use$>', $this->pageURL, $matches)) {
            $this->staticPage($matches, 'terms-of-use.php');

        } elseif (preg_match('<^/' . $langHandler . 'privacy-policy$>', $this->pageURL, $matches)) {
            $this->staticPage($matches, 'privacy-policy.php');

        } elseif (preg_match('<^/' . $langHandler . 'updates$>', $this->pageURL, $matches)) {
            $this->staticPage($matches, 'updates.php');

        } elseif (preg_match('<^/' . $langHandler . 'about-the-project$>', $this->pageURL, $matches)) {
            if (Translate::getCurrentLang() === 'en')
                $this->staticPage($matches, 'about-the-project_en.php');
            else
                $this->staticPage($matches, 'about-the-project.php');

        } elseif (preg_match('<^/' . $langHandler . 'partners$>', $this->pageURL, $matches)) {
            if (Translate::getCurrentLang() === 'en')
                $this->staticPage($matches, 'partners_en.php');
            else
                $this->staticPage($matches, 'partners.php');

        } elseif (preg_match('<^/' . $langHandler . 'open-data-research-and-resources$>', $this->pageURL, $matches)) {
            $this->staticPage($matches, 'open-data-research-and-resources.php');

        } elseif (preg_match('<^/' . $langHandler . 'contact-dsi$>', $this->pageURL, $matches)) {
            $this->staticPage($matches, 'contact-dsi.php');

// Permanent Login
        } elseif (preg_match('<^/' . $langHandler . 'keepUserLoggedIn>', $this->pageURL, $matches)) {
            $this->keepUserLoggedIn($matches);

// Terminate Account
        } elseif (preg_match('<^/' . $langHandler . 'terminateAccount>', $this->pageURL, $matches)) {
            $this->terminateAccount($matches);

// Sitemap
        } elseif (preg_match('<^/' . $langHandler . 'sitemap\.xml$>', $this->pageURL, $matches)) {
            $this->sitemapXml($matches);

// Unfiltered
        } elseif (preg_match('<^/' . $langHandler . 'manage/tags$>', $this->pageURL, $matches)) {
            $this->manageTags($matches);

        } elseif (preg_match('<^/' . $langHandler . 'manage/tags\.json$>', $this->pageURL, $matches)) {
            $this->manageTags($matches, 'json');

        } elseif (preg_match('<^/countryRegions/([0-9]+)\.json$>', $this->pageURL, $matches)) {
            $this->countryRegionsListJson($matches);

        } elseif (preg_match('<^/' . $langHandler . 'reset-password$>', $this->pageURL, $matches)) {
            $this->resetPassword($matches);

        } elseif (preg_match('<^/' . $langHandler . 'my-profile$>', $this->pageURL, $matches)) {
            $this->userProfile($matches);

        } elseif (preg_match('<^/' . $langHandler . 'profile/([a-zA-Z0-9\.]+)/details\.json$>', $this->pageURL, $matches)) {
            $this->userProfile($matches, 'json');

        } elseif (preg_match('<^/' . $langHandler . 'profile/([a-zA-Z0-9\.]+)\.json$>', $this->pageURL, $matches)) {
            $this->userProfile($matches, 'json');

        } elseif (preg_match('<^/' . $langHandler . 'profile/([a-zA-Z0-9\.]+)$>', $this->pageURL, $matches)) {
            $this->userProfile($matches);

        } elseif (preg_match('<^/' . $langHandler . 'project/([0-9]+)\.json?$>', $this->pageURL, $matches)) {
            $this->projectJson($matches);

        } elseif (preg_match('<^/' . $langHandler . 'project/edit/([0-9]+)\.json$>', $this->pageURL, $matches)) {
            $this->editProject($matches, 'json');

        } elseif (preg_match('<^/' . $langHandler . 'project/edit/([0-9]+)$>', $this->pageURL, $matches)) {
            $this->editProject($matches);

        } elseif (preg_match('<^/' . $langHandler . 'project/editOwner/([0-9]+)$>', $this->pageURL, $matches)) {
            $this->editProjectOwner($matches);

        } elseif (preg_match('<^/' . $langHandler . 'project/members/([0-9]+)$>', $this->pageURL, $matches)) {
            $this->editProjectMembers($matches);

        } elseif (preg_match('<^/' . $langHandler . 'project/members/([0-9]+)\.json$>', $this->pageURL, $matches)) {
            $this->editProjectMembers($matches, 'json');

        } elseif (preg_match('<^/projectPost/([0-9]+)\.json?$>', $this->pageURL, $matches)) {
            $this->projectPostJson($matches);

        } elseif (preg_match('<^/projectPostComment/([0-9]+)\.json?$>', $this->pageURL, $matches)) {
            $this->projectPostCommentJson($matches);

        } elseif (preg_match('<^/' . $langHandler . 'project/([0-9]+)(\/.*)?$>', $this->pageURL, $matches)) {
            $this->project($matches);

        } elseif (preg_match('<^/' . $langHandler . 'message/community-admins$>', $this->pageURL, $matches)) {
            $this->messageCommunityAdmins($matches);

        } elseif (preg_match('<^/' . $langHandler . 'waiting-approval$>', $this->pageURL, $matches)) {
            $this->waitingApproval($matches);

        } elseif (preg_match('<^/' . $langHandler . 'waiting-approval.json$>', $this->pageURL, $matches)) {
            $this->waitingApprovalJson($matches);

        } elseif (preg_match('<^/' . $langHandler . 'export/projects\.json$>', $this->pageURL, $matches)) {
            $this->exportProjects($matches, 'json');

        } elseif (preg_match('<^/' . $langHandler . 'export/projects\.csv$>', $this->pageURL, $matches)) {
            $this->exportProjects($matches, 'csv');

        } elseif (preg_match('<^/' . $langHandler . 'export/projects\.xml$>', $this->pageURL, $matches)) {
            $this->exportProjects($matches, 'xml');

        } elseif (preg_match('<^/' . $langHandler . 'export/organisations\.json$>', $this->pageURL, $matches)) {
            $this->exportOrganisations($matches, 'json');

        } elseif (preg_match('<^/' . $langHandler . 'export/organisations\.csv$>', $this->pageURL, $matches)) {
            $this->exportOrganisations($matches, 'csv');

        } elseif (preg_match('<^/' . $langHandler . 'export/organisations\.xml$>', $this->pageURL, $matches)) {
            $this->exportOrganisations($matches, 'xml');

        } elseif (preg_match('<^/' . $langHandler . 'clusters$>', $this->pageURL, $matches)) {
            $this->clusters($matches);

        } elseif (preg_match('<^/' . $langHandler . 'cluster/([0-9]+)$>', $this->pageURL, $matches)) {
            $this->cluster($matches);

        } elseif (preg_match('<^/' . $langHandler . 'cluster/([0-9]+)/edit$>', $this->pageURL, $matches)) {
            $this->clusterEdit($matches);

        } elseif (preg_match('<^/' . $langHandler . 'api/cluster/([0-9]+)$>', $this->pageURL, $matches)) {
            $this->clusterApi($matches);

        } elseif (preg_match('<^/' . $langHandler . 'api/cluster-image/([0-9]+)$>', $this->pageURL, $matches)) {
            $this->clusterImageApi($matches);

        } elseif (preg_match('<^/' . $langHandler . 'api/cluster-image/?$>', $this->pageURL, $matches)) {
            $this->clusterImageApi($matches);

        } elseif (preg_match('<^/' . $langHandler . 'api/email-subscribers/?$>', $this->pageURL, $matches)) {
            $this->emailSubscribersApi($matches);

        } elseif (preg_match('<^/.*\.(gif|jpe?g|png|svg|js|css|map|ico)$>', $this->pageURL)) {
            return $this->staticContent();

        } else {
            $this->notFound404();
        }

        return true;
    }

    private function register($matches = [], $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \Controllers\RegisterController();
        $command->responseFormat = $format;
        $command->exec();
    }

    private function login($matches = [], $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\LoginController();
        $command->responseFormat = $format;
        $command->exec();
    }

    private function homePage($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\HomeController();
        $command->format = 'html';
        $command->exec();
    }

    private function dashboard($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\DashboardController();
        $command->format = 'html';
        $command->exec();
    }

    private function dashboardJson($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\DashboardController();
        $command->format = 'json';
        $command->exec();
    }

    private function notificationsJson($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\NotificationsController();
        $command->exec();
    }

    private function forgotPasswordJson()
    {
        $command = new \DSI\Controller\ForgotPasswordController();
        $command->responseFormat = 'json';
        $command->exec();
    }

    private function facebookLogin()
    {
        $command = new \DSI\Controller\LoginFacebookController();
        $command->exec();
    }

    private function googleLogin()
    {
        $command = new \DSI\Controller\LoginGoogleController();
        $command->exec();
    }

    private function gitHubLogin()
    {
        $command = new \DSI\Controller\LoginGitHubController();
        $command->exec();
    }

    private function twitterLogin()
    {
        $command = new \DSI\Controller\LoginTwitterController();
        $command->exec();
    }

    private function registerJson()
    {
        $command = new \Controllers\RegisterController();
        $command->responseFormat = 'json';
        $command->exec();
    }

    private function setAdmin()
    {
        $command = new \DSI\Controller\SetAdminController();
        $command->exec();
    }

    private function logout($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\LogoutController();
        $command->exec();
    }

    private function stories($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\StoriesController();
        $command->exec();
    }

    private function storiesJson($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\StoriesController();
        $command->format = 'json';
        $command->exec();
    }

    private function addStory($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\StoryAddController();
        $command->exec();
    }

    private function addCaseStudy($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\CaseStudyAddController();
        $command->exec();
    }

// Funding
    private function funding($matches = [], $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\FundingController();
        $command->format = $format;
        $command->exec();
    }

    private function addFunding($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\FundingAddController();
        $command->exec();
    }

    private function editFunding($matches = [], $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\FundingEditController();
        $command->fundingID = $matches[3];
        $command->format = $format;
        $command->exec();
    }

// Events
    private function events($matches = [], $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\EventsController();
        $command->format = $format;
        $command->exec();
    }

    private function addEvent($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\EventAddController();
        $command->exec();
    }

    private function editEvent($matches = [], $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\EventEditController();
        $command->eventID = $matches[3];
        $command->format = $format;
        $command->exec();
    }

    private function event($matches = [], $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\EventController();
        $command->eventID = $matches[3];
        $command->format = $format;
        $command->exec();
    }


    private function projects($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\ProjectsController();
        $command->exec();
    }

    private function projectsJson($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\ProjectsController();
        $command->responseFormat = 'json';
        $command->exec();
    }

    private function projectTagsJson($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\ProjectTagsController();
        $command->responseFormat = 'json';
        $command->exec();
    }

    private function organisationTagsJson($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\OrganisationTagsController();
        $command->responseFormat = 'json';
        $command->exec();
    }

    private function organisations($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\OrganisationsController();
        $command->exec();
    }

    private function organisationsJson($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\OrganisationsController();
        $command->responseFormat = 'json';
        $command->exec();
    }

    /**
     * @param $matches
     * @param string $format
     */
    private function userProfile($matches, $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\ProfileController();
        $command->setFormat($format);
        $command->setUserURL($matches[3] ?? '');
        $command->exec();
    }

    /**
     * @param $matches
     * @param string $format
     */
    private function resetPassword($matches, $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\ResetPasswordController();
        $command->exec();
    }

    /**
     * @return bool
     */
    private function staticContent()
    {
        return false;
    }

    private function notFound404()
    {
        $command = new \DSI\Controller\NotFound404Controller();
        $command->exec();
    }

    private function skillsListJson()
    {
        $command = new \DSI\Controller\ListSkillsController();
        $command->exec();
    }

    private function languagesListJson()
    {
        $command = new \DSI\Controller\ListLanguagesController();
        $command->exec();
    }

    private function profileEdit($matches, $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\ProfileEditController();
        $command->userID = $matches[3];
        $command->format = $format;
        $command->exec();
    }

    private function profileEditPrivileges($matches, $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\ProfileEditPrivilegesController();
        $command->userID = $matches[3];
        $command->exec();
    }

    private function uploadProfilePicture()
    {
        $command = new \DSI\Controller\ProfileEditController();
        $command->exec();
    }

    private function createProject($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\CreateProjectController();
        $command->exec();
    }

    private function createOrganisation($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\CreateOrganisationController();
        $command->exec();
    }

    private function project($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\ProjectController();
        $command->data()->projectID = $matches[3];
        $command->exec();
    }

    private function caseStudy($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\CaseStudyController();
        $command->caseStudyID = $matches[3];
        $command->exec();
    }

    private function messageCommunityAdmins($matches)
    {
        $this->setLanguageFromUrl($matches);
        return (new \DSI\Controller\MessageCommunityAdminsController())->exec();
    }

    private function waitingApproval($matches)
    {
        $this->setLanguageFromUrl($matches);
        return (new \DSI\Controller\WaitingApprovalController())
            ->html();
    }

    private function waitingApprovalJson($matches)
    {
        $this->setLanguageFromUrl($matches);
        return (new \DSI\Controller\WaitingApprovalController())
            ->json();
    }

    private function exportProjects($matches, $format = 'json')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\ExportProjectsController();
        $command->format = $format;
        $command->exec();
    }

    private function exportOrganisations($matches, $format = 'json')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\ExportOrganisationsController();
        $command->format = $format;
        $command->exec();
    }

    private function story($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\StoryController();
        $command->data()->storyID = $matches[3];
        $command->exec();
    }

    private function editStory($matches, $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\StoryEditController();
        $command->storyID = $matches[3];
        $command->format = $format;
        $command->exec();
    }

    private function organisation($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\OrganisationController();
        $command->data()->organisationID = $matches[3];
        $command->exec();
    }

    private function projectJson($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\ProjectController();
        $command->data()->projectID = $matches[3];
        $command->data()->format = 'json';
        $command->exec();
    }

    private function editProject($matches, $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\ProjectEditController();
        $command->projectID = $matches[3];
        $command->format = $format;
        $command->exec();
    }

    private function editProjectOwner($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\ProjectEditOwnerController();
        $command->projectID = $matches[3];
        $command->exec();
    }

    private function editProjectMembers($matches, $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\ProjectEditMembersController();
        $command->format = $format;
        $command->projectID = $matches[3];
        $command->exec();
    }

    private function editOrganisation($matches, $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\OrganisationEditController();
        $command->organisationID = $matches[3];
        $command->format = $format;
        $command->exec();
    }

    private function editOrganisationOwner($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\OrganisationEditOwnerController();
        $command->organisationID = $matches[3];
        $command->exec();
    }

    private function editOrganisationsMembers($matches, $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\OrganisationEditMembersController();
        $command->organisationID = $matches[3];
        $command->format = $format;
        $command->exec();
    }

    private function editCaseStudy($matches, $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\CaseStudyEditController();
        $command->caseStudyID = $matches[3];
        $command->format = $format;
        $command->exec();
    }

    private function search($matches, $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\SearchController();
        $command->term = $matches[3] ?? '';
        $command->format = $format;
        $command->exec();
    }

    private function caseStudies($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\CaseStudiesController();
        $command->exec();
    }

    private function caseStudiesJson($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\CaseStudiesController();
        $command->format = 'json';
        $command->exec();
    }

    private function projectPostJson($matches)
    {
        $command = new \DSI\Controller\ProjectPostController();
        $command->data()->postID = $matches[1];
        $command->data()->format = 'json';
        $command->exec();
    }

    private function projectPostCommentJson($matches)
    {
        $command = new \DSI\Controller\ProjectPostCommentController();
        $command->data()->commentID = $matches[1];
        $command->data()->format = 'json';
        $command->exec();
    }

    /*
    private function organisationJsonPage($matches)
    {
        if (isset($matches[2]) AND $matches[2])
            $this->setLanguage($matches[2]);

        $command = new \DSI\Controller\OrganisationController();
        $command->data()->organisationID = $matches[3];
        $command->data()->format = 'json';
        $command->exec();
    }
    */

    private function tagsForProjectsListJson()
    {
        $command = new \DSI\Controller\ListTagsForProjectsController();
        $command->exec();
    }

    private function tagsForOrganisationsListJson()
    {
        $command = new \DSI\Controller\ListTagsForOrganisationsController();
        $command->exec();
    }

    private function impactTagsListJson()
    {
        $command = new \DSI\Controller\ListImpactTagsController();
        $command->exec();
    }

    private function usersListJson()
    {
        $command = new \DSI\Controller\ListUsersController();
        $command->exec();
    }

    private function countriesListJson()
    {
        $command = new \DSI\Controller\ListCountriesController();
        $command->exec();
    }

    private function organisationsListJson()
    {
        $command = new \DSI\Controller\ListOrganisationsController();
        $command->exec();
    }

    private function countryRegionsListJson($matches)
    {
        $command = new \DSI\Controller\ListCountryRegionsController();
        $command->data()->countryID = $matches[1];
        $command->exec();
    }

    private function manageTags($matches, $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\ManageTagsController();
        $command->responseFormat = $format;
        $command->exec();
    }

    private function feedback($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\FeedbackController();
        $command->exec();
    }

    private function feedbackJson($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\FeedbackController();
        $command->format = 'json';
        $command->exec();
    }

    private function rssNewsBlogs()
    {
        $command = new \DSI\Controller\RssNewsBlogsController();
        $command->exec();
    }

    private function rssEvents()
    {
        $command = new \DSI\Controller\RssEventsController();
        $command->exec();
    }

    private function rssFundingOpportunities()
    {
        $command = new \DSI\Controller\RssFundingOpportunitiesController();
        $command->exec();
    }

    private function robotsTxt()
    {
        $command = new \DSI\Controller\StaticHtmlController();
        $command->format = 'txt';
        $command->view = 'robots.txt.php';
        $command->exec();
    }

    private function staticPage($matches, $view)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\StaticHtmlController();
        $command->view = $view;
        $command->exec();
    }

    private function keepUserLoggedIn($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\KeepUserLoggedInController();
        $command->exec();
    }

    private function terminateAccount($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \Controllers\TerminateAccountController();
        $command->exec();
    }

    private function sitemapXml($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\SitemapController();
        $command->format = 'xml';
        $command->exec();
    }

    private function tempGalleryJson()
    {
        $command = new \Controllers\TempGalleryController();
        $command->format = 'json';
        $command->exec();
    }

    private function uploadImageJson()
    {
        $command = new \DSI\Controller\UploadImageController();
        $command->format = 'json';
        $command->exec();
    }

    private function clusters($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \Controllers\ClustersController();
        $command->exec();
    }

    private function cluster($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \Controllers\ClusterController($matches[3]);
        $command->get();
    }

    private function clusterEdit($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \Controllers\ClusterController($matches[3]);
        $command->edit();
    }

    private function clusterApi($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \Controllers\API\ClusterApiController();
        $command->clusterID = $matches[3];
        $command->exec();
    }

    private function clusterImageApi($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \Controllers\API\ClusterImgApiController();
        $command->clusterImgID = isset($matches[3]) ? $matches[3] : 0;
        $command->exec();
    }

    private function emailSubscribersApi($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \Controllers\API\EmailSubscribersApiController();
        $command->exec();
    }

    public function forceHTTPS()
    {
        if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off") {
            $redirect = 'https://' . SITE_DOMAIN . $_SERVER['REQUEST_URI'];
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $redirect);
            exit();
        }
    }

    private function setLanguage($lang)
    {
        Translate::setCurrentLang($lang);
    }

    /**
     * @param $matches
     */
    private function setLanguageFromUrl($matches)
    {
        if (isset($matches[2]) AND $matches[2])
            $this->setLanguage($matches[2]);
    }
}