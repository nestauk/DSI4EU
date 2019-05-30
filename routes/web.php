<?php

use Controllers\TableauVis;
use Controllers\AcceptPolicyController;
use Controllers\AdvisoryBoardController;
use Controllers\API\ClusterApiController;
use Controllers\API\ClusterImgApiController;
use Controllers\API\EmailSubscribersApiController;
use Controllers\API\OpenResourceApiController;
use Controllers\CaseStudies\CaseStudiesController;
use Controllers\CaseStudies\CaseStudyAddController;
use Controllers\CaseStudies\CaseStudyController;
use Controllers\CaseStudies\CaseStudyEditController;
use Controllers\ClusterController;
use Controllers\ClustersController;
use Controllers\DashboardController;
use Controllers\DsiIndex\DsiIndexController;
use Controllers\Futures\FuturesController;
use Controllers\HomeController;
use Controllers\OpenData;
use Controllers\OpenDataEdit;
use Controllers\Projects\ProjectController;
use Controllers\Projects\ProjectEditController;
use Controllers\RegisterController;
use Controllers\ResourceCreate;
use Controllers\ResourceEdit;
use Controllers\StaticHtmlController;
use Controllers\Stories\StoriesController;
use Controllers\Stories\StoryAddController;
use Controllers\Stories\StoryController;
use Controllers\Stories\StoryEditController;
use Controllers\TempGalleryController;
use Controllers\TerminateAccountController;
use Controllers\UploadController;
use Controllers\WhatIsDsiController;
use DSI\Controller\CreateOrganisationController;
use DSI\Controller\CreateProjectController;
use DSI\Controller\EventAddController;
use DSI\Controller\EventController;
use DSI\Controller\EventEditController;
use DSI\Controller\EventsController;
use DSI\Controller\ExportOrganisationsController;
use DSI\Controller\ExportProjectsController;
use DSI\Controller\FeedbackController;
use DSI\Controller\ForgotPasswordController;
use DSI\Controller\FundingAddController;
use DSI\Controller\FundingController;
use DSI\Controller\FundingEditController;
use DSI\Controller\KeepUserLoggedInController;
use DSI\Controller\ListCountriesController;
use DSI\Controller\ListCountryRegionsController;
use DSI\Controller\ListImpactTagsController;
use DSI\Controller\ListLanguagesController;
use DSI\Controller\ListOrganisationsController;
use DSI\Controller\ListSkillsController;
use DSI\Controller\ListTagsForOrganisationsController;
use DSI\Controller\ListTagsForProjectsController;
use DSI\Controller\ListUsersController;
use DSI\Controller\LoginController;
use DSI\Controller\LoginFacebookController;
use DSI\Controller\LoginGitHubController;
use DSI\Controller\LoginGoogleController;
use DSI\Controller\LoginTwitterController;
use DSI\Controller\LogoutController;
use DSI\Controller\ManageTagsController;
use DSI\Controller\MessageCommunityAdminsController;
use DSI\Controller\NotFound404Controller;
use DSI\Controller\NotificationsController;
use DSI\Controller\OrganisationController;
use DSI\Controller\OrganisationEditController;
use DSI\Controller\OrganisationEditMembersController;
use DSI\Controller\OrganisationEditOwnerController;
use DSI\Controller\OrganisationsController;
use DSI\Controller\OrganisationTagsController;
use DSI\Controller\ProfileController;
use DSI\Controller\ProfileEditController;
use DSI\Controller\ProfileEditPrivilegesController;
use DSI\Controller\ProjectEditMembersController;
use DSI\Controller\ProjectEditOwnerController;
use DSI\Controller\ProjectPostCommentController;
use DSI\Controller\ProjectPostController;
use DSI\Controller\ProjectsController;
use DSI\Controller\ProjectTagsController;
use DSI\Controller\ResetPasswordController;
use DSI\Controller\RssEventsController;
use DSI\Controller\RssFundingOpportunitiesController;
use DSI\Controller\RssNewsBlogsController;
use DSI\Controller\SearchController;
use DSI\Controller\SetAdminController;
use DSI\Controller\SitemapController;
use DSI\Controller\UploadImageController;
use \DSI\Service\Translate;
use \DSI\Entity\Translation;
use \Controllers\Upload\UploadResourcesController;
use \DSI\Controller\WaitingApprovalController;

class Router
{
    /** @var string */
    private $pageURL;

    public function exec(string $pageURL)
    {
        Translate::setCurrentLang(Translation::DEFAULT_LANGUAGE);
        // $this->pageURL = $pageURL;
        $this->pageURL = rtrim($pageURL, '/');
        if ($this->pageURL === '')
            $this->pageURL = '/';

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

        } elseif (preg_match('<^/' . $langHandler . 'accept-policy>', $this->pageURL, $matches)) {
            $this->acceptPolicy($matches);

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

        } elseif ($this->pageURL === '/upload-file') {
            $this->uploadFile();

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

        } elseif ($this->pageURL === '/rss/funding-opportunities.xml')
            $this->rssFundingOpportunities();

// Admin
        elseif ($this->pageURL === '/upload/resources')
            return (new UploadResourcesController())->handle();

        elseif ($this->pageURL === '/upload/resources/save')
            return (new UploadResourcesController())->setSave(true)->handle();

// Static pages
        elseif ($this->pageURL === '/robots.txt') {
            $this->robotsTxt();

        } elseif (preg_match('<^/' . $langHandler . 'advisory-board$>', $this->pageURL, $matches)) {
            $this->advisoryBoard($matches);

        } elseif (preg_match('<^/' . $langHandler . 'explore-dsi$>', $this->pageURL, $matches)) {
            $this->staticPage($matches, 'explore-dsi.php');

        } elseif (preg_match('<^/' . $langHandler . 'terms-of-use$>', $this->pageURL, $matches)) {
            $this->staticPage($matches, 'terms-of-use.php');

        } elseif (preg_match('<^/' . $langHandler . 'privacy-policy$>', $this->pageURL, $matches)) {
            $this->privacyPolicy($matches);

        } elseif (preg_match('<^/' . $langHandler . 'updates$>', $this->pageURL, $matches)) {
            $this->staticPage($matches, 'updates.php');


        } elseif (preg_match('<^/' . $langHandler . 'dsi-index/viz$>', $this->pageURL, $matches)) {
            $this->tableauVis($matches);

        } elseif (preg_match('<^/' . $langHandler . 'dsi-index>', $this->pageURL, $matches)) {
            $this->dsiIndex($matches);

        } elseif (preg_match('<^/' . $langHandler . 'edit/dsi-index$>', $this->pageURL, $matches)) {
            $this->dsiIndexEdit($matches);


        } elseif (preg_match('<^/' . $langHandler . 'futures>', $this->pageURL, $matches)) {
            $this->futures($matches);

        } elseif (preg_match('<^/' . $langHandler . 'edit/futures$>', $this->pageURL, $matches)) {
            $this->futuresEdit($matches);


        } elseif (preg_match('<^/' . $langHandler . 'about-the-project$>', $this->pageURL, $matches)) {
            $this->about($matches);

        } elseif (preg_match('<^/' . $langHandler . 'partners$>', $this->pageURL, $matches)) {
            $this->partners($matches);

        } elseif (preg_match('<^/' . $langHandler . 'open-data-research-and-resources$>', $this->pageURL, $matches)) {
            $this->openData($matches);

        } elseif (preg_match('<^/' . $langHandler . 'edit/open-data-research-and-resources$>', $this->pageURL, $matches)) {
            $this->openDataEdit($matches);

        } elseif (preg_match('<^/' . $langHandler . 'api/open-resources$>', $this->pageURL, $matches)) {
            $this->openResourcesApi($matches);

        } elseif (preg_match('<^/' . $langHandler . 'open-resource/edit/new$>', $this->pageURL, $matches)) {
            $this->newResource($matches);

        } elseif (preg_match('<^/' . $langHandler . 'open-resource/edit/([0-9]+)?$>', $this->pageURL, $matches)) {
            $this->editResource($matches);

        } elseif (preg_match('<^/' . $langHandler . 'api/open-resource/([0-9]+)$>', $this->pageURL, $matches)) {
            $this->openResourceEditApi($matches);

        } elseif (preg_match('<^/' . $langHandler . 'api/open-resource$>', $this->pageURL, $matches)) {
            $this->openResourceCreateApi($matches);

        } elseif (preg_match('<^/' . $langHandler . 'contact-dsi$>', $this->pageURL, $matches)) {
            $this->contact($matches);

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
            $this->setLanguageFromUrl($matches);
            return (new WaitingApprovalController())->html();

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

        } elseif (preg_match('<^/' . $langHandler . 'cluster/([0-9]+)/edit$>', $this->pageURL, $matches)) {
            $this->clusterEdit($matches);

        } elseif (preg_match('<^/' . $langHandler . 'cluster/([0-9]+)/?>', $this->pageURL, $matches)) {
            $this->cluster($matches);

        } elseif (preg_match('<^/' . $langHandler . 'api/cluster/([0-9]+)$>', $this->pageURL, $matches)) {
            $this->clusterApi($matches);

        } elseif (preg_match('<^/' . $langHandler . 'api/cluster-image/([0-9]+)$>', $this->pageURL, $matches)) {
            $this->clusterImageApi($matches);

        } elseif (preg_match('<^/' . $langHandler . 'api/cluster-image$>', $this->pageURL, $matches)) {
            $this->clusterImageApi($matches);

        } elseif (preg_match('<^/' . $langHandler . 'api/email-subscribers$>', $this->pageURL, $matches)) {
            $this->emailSubscribersApi($matches);

        } elseif (preg_match('<^/' . $langHandler . 'cookies-policy$>', $this->pageURL, $matches)) {
            $this->cookiesPolicy($matches);

        } elseif (preg_match('<^/' . $langHandler . 'what-is-dsi$>', $this->pageURL, $matches)) {
            $this->whatIsDsi($matches);

        } elseif (preg_match('<^/.*\.(gif|jpe?g|png|svg|js|css|map|ico|csv)$>', $this->pageURL)) {
            return $this->staticContent();

        } else {
            $this->notFound404();
        }

        return true;
    }

    private function register($matches = [], $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new RegisterController();
        $command->responseFormat = $format;
        $command->exec();
    }

    private function acceptPolicy($matches = [], $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new AcceptPolicyController();
        $command->responseFormat = $format;
        $command->exec();
    }

    private function login($matches = [], $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new LoginController();
        $command->responseFormat = $format;
        $command->exec();
    }

    private function homePage($matches = [])
    {
        $this->setLanguageFromUrl($matches);
        return (new HomeController())->exec();
    }

    private function dashboard($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new DashboardController();
        $command->format = 'html';
        $command->exec();
    }

    private function dashboardJson($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new DashboardController();
        $command->format = 'json';
        $command->exec();
    }

    private function notificationsJson($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new NotificationsController();
        $command->exec();
    }

    private function forgotPasswordJson()
    {
        $command = new ForgotPasswordController();
        $command->responseFormat = 'json';
        $command->exec();
    }

    private function facebookLogin()
    {
        $command = new LoginFacebookController();
        $command->exec();
    }

    private function googleLogin()
    {
        $command = new LoginGoogleController();
        $command->exec();
    }

    private function gitHubLogin()
    {
        $command = new LoginGitHubController();
        $command->exec();
    }

    private function twitterLogin()
    {
        $command = new LoginTwitterController();
        $command->exec();
    }

    private function registerJson()
    {
        $command = new RegisterController();
        $command->responseFormat = 'json';
        $command->exec();
    }

    private function setAdmin()
    {
        $command = new SetAdminController();
        $command->exec();
    }

    private function logout($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new LogoutController();
        $command->exec();
    }

    private function stories($matches)
    {
        $this->setLanguageFromUrl($matches);
        (new StoriesController())->exec();
    }

    private function storiesJson($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new StoriesController();
        $command->format = 'json';
        $command->exec();
    }

    private function addStory($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new StoryAddController();
        $command->exec();
    }

    private function addCaseStudy($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new CaseStudyAddController();
        $command->exec();
    }

// Funding
    private function funding($matches = [], $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new FundingController();
        $command->format = $format;
        $command->exec();
    }

    private function addFunding($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new FundingAddController();
        $command->exec();
    }

    private function editFunding($matches = [], $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new FundingEditController();
        $command->fundingID = $matches[3];
        $command->format = $format;
        $command->exec();
    }

// Events
    private function events($matches = [], $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new EventsController();
        $command->format = $format;
        $command->exec();
    }

    private function addEvent($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new EventAddController();
        $command->exec();
    }

    private function editEvent($matches = [], $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new EventEditController();
        $command->eventID = $matches[3];
        $command->format = $format;
        $command->exec();
    }

    private function event($matches = [], $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new EventController();
        $command->eventID = $matches[3];
        $command->format = $format;
        $command->exec();
    }


    private function projects($matches = [])
    {
        $this->setLanguageFromUrl($matches);
        (new ProjectsController())->exec();
    }

    private function projectsJson($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new ProjectsController();
        $command->responseFormat = 'json';
        $command->exec();
    }

    private function projectTagsJson($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new ProjectTagsController();
        $command->responseFormat = 'json';
        $command->exec();
    }

    private function organisationTagsJson($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new OrganisationTagsController();
        $command->responseFormat = 'json';
        $command->exec();
    }

    private function organisations($matches)
    {
        $this->setLanguageFromUrl($matches);
        (new OrganisationsController())->exec();
    }

    private function organisationsJson($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new OrganisationsController();
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

        $command = new ProfileController();
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

        $command = new ResetPasswordController();
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
        $command = new NotFound404Controller();
        $command->exec();
    }

    private function skillsListJson()
    {
        $command = new ListSkillsController();
        $command->exec();
    }

    private function languagesListJson()
    {
        $command = new ListLanguagesController();
        $command->exec();
    }

    private function profileEdit($matches, $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new ProfileEditController();
        $command->userID = $matches[3];
        $command->format = $format;
        $command->exec();
    }

    private function profileEditPrivileges($matches, $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new ProfileEditPrivilegesController();
        $command->userID = $matches[3];
        $command->exec();
    }

    private function uploadProfilePicture()
    {
        $command = new ProfileEditController();
        $command->exec();
    }

    private function createProject($matches)
    {
        $this->setLanguageFromUrl($matches);
        (new CreateProjectController())->exec();
    }

    private function createOrganisation($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new CreateOrganisationController();
        $command->exec();
    }

    private function project($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new ProjectController();
        $command->data()->projectID = $matches[3];
        $command->exec();
    }

    private function caseStudy($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new CaseStudyController();
        $command->caseStudyID = $matches[3];
        $command->exec();
    }

    private function messageCommunityAdmins($matches)
    {
        $this->setLanguageFromUrl($matches);
        return (new MessageCommunityAdminsController())->exec();
    }

    private function waitingApprovalJson($matches)
    {
        $this->setLanguageFromUrl($matches);
        return (new WaitingApprovalController())
            ->json();
    }

    private function exportProjects($matches, $format = 'json')
    {
        $this->setLanguageFromUrl($matches);

        $command = new ExportProjectsController();
        $command->format = $format;
        $command->exec();
    }

    private function exportOrganisations($matches, $format = 'json')
    {
        $this->setLanguageFromUrl($matches);

        $command = new ExportOrganisationsController();
        $command->format = $format;
        $command->exec();
    }

    private function story($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new StoryController();
        $command->data()->storyID = $matches[3];
        $command->exec();
    }

    private function editStory($matches, $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new StoryEditController();
        $command->storyID = $matches[3];
        $command->format = $format;
        $command->exec();
    }

    private function organisation($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new OrganisationController();
        $command->data()->organisationID = $matches[3];
        $command->exec();
    }

    private function projectJson($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new ProjectController();
        $command->data()->projectID = $matches[3];
        $command->data()->format = 'json';
        $command->exec();
    }

    private function editProject($matches, $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new ProjectEditController();
        $command->projectID = $matches[3];
        $command->format = $format;
        $command->exec();
    }

    private function editProjectOwner($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new ProjectEditOwnerController();
        $command->projectID = $matches[3];
        $command->exec();
    }

    private function editProjectMembers($matches, $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new ProjectEditMembersController();
        $command->format = $format;
        $command->projectID = $matches[3];
        $command->exec();
    }

    private function editOrganisation($matches, $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new OrganisationEditController();
        $command->organisationID = $matches[3];
        $command->format = $format;
        $command->exec();
    }

    private function editOrganisationOwner($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new OrganisationEditOwnerController();
        $command->organisationID = $matches[3];
        $command->exec();
    }

    private function editOrganisationsMembers($matches, $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new OrganisationEditMembersController();
        $command->organisationID = $matches[3];
        $command->format = $format;
        $command->exec();
    }

    private function editCaseStudy($matches, $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new CaseStudyEditController();
        $command->caseStudyID = $matches[3];
        $command->format = $format;
        $command->exec();
    }

    private function search($matches, $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new SearchController();
        $command->term = $matches[3] ?? '';
        $command->format = $format;
        $command->exec();
    }

    private function caseStudies($matches)
    {
        $this->setLanguageFromUrl($matches);
        (new CaseStudiesController())->exec();
    }

    private function caseStudiesJson($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new CaseStudiesController();
        $command->format = 'json';
        $command->exec();
    }

    private function projectPostJson($matches)
    {
        $command = new ProjectPostController();
        $command->data()->postID = $matches[1];
        $command->data()->format = 'json';
        $command->exec();
    }

    private function projectPostCommentJson($matches)
    {
        $command = new ProjectPostCommentController();
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
        $command = new ListTagsForProjectsController();
        $command->exec();
    }

    private function tagsForOrganisationsListJson()
    {
        $command = new ListTagsForOrganisationsController();
        $command->exec();
    }

    private function impactTagsListJson()
    {
        $command = new ListImpactTagsController();
        $command->exec();
    }

    private function usersListJson()
    {
        $command = new ListUsersController();
        $command->exec();
    }

    private function countriesListJson()
    {
        $command = new ListCountriesController();
        $command->exec();
    }

    private function organisationsListJson()
    {
        $command = new ListOrganisationsController();
        $command->exec();
    }

    private function countryRegionsListJson($matches)
    {
        $command = new ListCountryRegionsController();
        $command->data()->countryID = $matches[1];
        $command->exec();
    }

    private function tableauVis($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new TableauVis();
        $command->exec();
    }

    private function manageTags($matches, $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new ManageTagsController();
        $command->responseFormat = $format;
        $command->exec();
    }

    private function feedback($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new FeedbackController();
        $command->exec();
    }

    private function feedbackJson($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new FeedbackController();
        $command->format = 'json';
        $command->exec();
    }

    private function rssNewsBlogs()
    {
        $command = new RssNewsBlogsController();
        $command->exec();
    }

    private function rssEvents()
    {
        $command = new RssEventsController();
        $command->exec();
    }

    private function rssFundingOpportunities()
    {
        (new RssFundingOpportunitiesController())->exec();
    }

    private function robotsTxt()
    {
        $command = new \DSI\Controller\StaticHtmlController();
        $command->format = 'txt';
        $command->view = 'robots.txt.php';
        $command->exec();
    }

    private function contact($matches)
    {
        $this->setLanguageFromUrl($matches);
        (new \DSI\Controller\StaticHtmlController())->contact();
    }

    private function about($matches)
    {
        $this->setLanguageFromUrl($matches);
        (new \DSI\Controller\StaticHtmlController())->about();
    }


    private function dsiIndex($matches)
    {
        $this->setLanguageFromUrl($matches);
        return (new DsiIndexController())->exec();
    }

    private function dsiIndexEdit($matches)
    {
        $this->setLanguageFromUrl($matches);
        return (new DsiIndexController())->edit();
    }


    private function futures($matches)
    {
        $this->setLanguageFromUrl($matches);
        return (new FuturesController())->exec();
    }

    private function futuresEdit($matches)
    {
        $this->setLanguageFromUrl($matches);
        return (new FuturesController())->edit();
    }


    private function advisoryBoard($matches)
    {
        $this->setLanguageFromUrl($matches);
        return (new AdvisoryBoardController())->exec();
    }

    private function staticPage($matches, $view)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\StaticHtmlController();
        $command->view = $view;
        $command->exec();
    }

    private function privacyPolicy($matches)
    {
        $this->setLanguageFromUrl($matches);
        return (new StaticHtmlController())->privacyPolicy();
    }

    private function partners($matches)
    {
        $this->setLanguageFromUrl($matches);
        return (new StaticHtmlController())->partners();
    }

    private function openData($matches)
    {
        $this->setLanguageFromUrl($matches);
        return (new OpenData())->exec();
    }

    private function openDataEdit($matches)
    {
        $this->setLanguageFromUrl($matches);
        return (new OpenDataEdit())->exec();
    }

    private function newResource($matches)
    {
        $this->setLanguageFromUrl($matches);
        return (new ResourceCreate())->exec();
    }

    private function editResource($matches)
    {
        $this->setLanguageFromUrl($matches);
        return (new ResourceEdit($matches[3]))->exec();
    }

    private function openResourceCreateApi($matches)
    {
        $this->setLanguageFromUrl($matches);
        return (new OpenResourceApiController())->createObject();
    }

    private function openResourcesApi($matches)
    {
        $this->setLanguageFromUrl($matches);
        return (new OpenResourceApiController())->getObjects();
    }

    private function openResourceEditApi($matches)
    {
        $this->setLanguageFromUrl($matches);
        return (new OpenResourceApiController())->edit($matches[3]);
    }

    private function keepUserLoggedIn($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new KeepUserLoggedInController();
        $command->exec();
    }

    private function terminateAccount($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new TerminateAccountController();
        $command->exec();
    }

    private function sitemapXml($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new SitemapController();
        $command->format = 'xml';
        $command->exec();
    }

    private function tempGalleryJson()
    {
        $command = new TempGalleryController();
        $command->format = 'json';
        $command->exec();
    }

    private function uploadImageJson()
    {
        $command = new UploadImageController();
        $command->format = 'json';
        $command->exec();
    }

    private function uploadFile()
    {
        return (new UploadController())->exec();
    }

    private function clusters($matches)
    {
        $this->setLanguageFromUrl($matches);
        (new ClustersController())->exec();
    }

    private function cluster($matches)
    {
        $this->setLanguageFromUrl($matches);
        (new ClusterController($matches[3]))->get();
    }

    private function clusterEdit($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new ClusterController($matches[3]);
        $command->edit();
    }

    private function clusterApi($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new ClusterApiController();
        $command->clusterID = $matches[3];
        $command->exec();
    }

    private function clusterImageApi($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new ClusterImgApiController();
        $command->clusterImgID = isset($matches[3]) ? $matches[3] : 0;
        $command->exec();
    }

    private function emailSubscribersApi($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new EmailSubscribersApiController();
        $command->exec();
    }

    private function cookiesPolicy($matches)
    {
        $this->setLanguageFromUrl($matches);
        return (new StaticHtmlController())->cookiesPolicy();
    }

    private function whatIsDsi($matches)
    {
        $this->setLanguageFromUrl($matches);
        (new WhatIsDsiController())->get();
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
