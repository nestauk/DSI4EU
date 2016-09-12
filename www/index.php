<?php

use \DSI\Service\Translate;
use \DSI\Entity\Translation;

require __DIR__ . '/../src/config.php';

if (!$_POST) {
    $postData = file_get_contents("php://input");
    $_POST = json_decode($postData, true);
}

class Router
{
    /** @var string */
    private $pageURL;

    public function exec(string $pageURL)
    {
        Translate::setCurrentLang(Translation::DEFAULT_LANGUAGE);
        $urlHandler = new \DSI\Service\URL();
        $this->pageURL = $pageURL;

        $langHandler = '(([a-z]{2})/?)?';

        if (preg_match('<^/' . $langHandler . '$>', $this->pageURL, $matches)) {
            $this->homePage($matches);

        } elseif (preg_match('<^/' . $langHandler . 'dashboard$>', $this->pageURL, $matches)) {
            $this->dashboardPage($matches);

        } elseif (preg_match('<^/' . $langHandler . 'dashboard\.json$>', $this->pageURL, $matches)) {
            $this->dashboardJsonPage($matches);

        } elseif ($this->pageURL === '/import') {
            $this->importPage();

        } elseif ($this->pageURL === '/forgotPassword.json') {
            $this->forgotPasswordJsonPage();

        } elseif ($this->pageURL === '/facebook-login') {
            $this->facebookLoginPage();

        } elseif ($this->pageURL === '/google-login') {
            $this->googleLoginPage();

        } elseif ($this->pageURL === '/github-login') {
            $this->gitHubLoginPage();

        } elseif ($this->pageURL === '/twitter-login') {
            $this->twitterLoginPage();

        } elseif ($this->pageURL === '/app-login') {
            $this->appLoginPage();

        } elseif ($this->pageURL === '/app-register-user') {
            $this->appRegisterUserPage();

        } elseif ($this->pageURL === '/set-admin') {
            $this->setAdminPage();

        } elseif ($this->pageURL === '/register.json') {
            $this->registerJsonPage();

        } elseif (preg_match('<^/' . $langHandler . 'login\.json$>', $this->pageURL, $matches)) {
            $this->loginJsonPage($matches);

        } elseif (preg_match('<^/' . $langHandler . 'login$>', $this->pageURL, $matches)) {
            go_to($urlHandler->home());

        } elseif (preg_match('<^/' . $langHandler . 'logout$>', $this->pageURL, $matches)) {
            $this->logoutPage($matches);

// Funding
        } elseif (preg_match('<^/' . $langHandler . 'funding$>', $this->pageURL, $matches)) {
            $this->fundingPage($matches);

        } elseif (preg_match('<^/' . $langHandler . 'funding\.json$>', $this->pageURL, $matches)) {
            $this->fundingPage($matches, 'json');

        } elseif (preg_match('<^/' . $langHandler . 'funding/add$>', $this->pageURL, $matches)) {
            $this->addFundingPage($matches);

        } elseif (preg_match('<^/' . $langHandler . 'funding/edit/([0-9]+)$>', $this->pageURL, $matches)) {
            $this->editFundingPage($matches);

        } elseif (preg_match('<^/' . $langHandler . 'funding/edit/([0-9]+)\.json$>', $this->pageURL, $matches)) {
            $this->editFundingPage($matches, 'json');

// Events
        } elseif (preg_match('<^/' . $langHandler . 'events$>', $this->pageURL, $matches)) {
            $this->eventsPage($matches);

        } elseif (preg_match('<^/' . $langHandler . 'events\.json$>', $this->pageURL, $matches)) {
            $this->eventsPage($matches, 'json');

        } elseif (preg_match('<^/' . $langHandler . 'events/add$>', $this->pageURL, $matches)) {
            $this->addEventPage($matches);

// Projects
        } elseif (preg_match('<^/' . $langHandler . 'projects$>', $this->pageURL, $matches)) {
            $this->projectsPage($matches);

        } elseif (preg_match('<^/' . $langHandler . 'projects\.json$>', $this->pageURL, $matches)) {
            $this->projectsJsonPage($matches);

// Organisations
        } elseif (preg_match('<^/' . $langHandler . 'organisations$>', $this->pageURL, $matches)) {
            $this->organisationsPage($matches);

        } elseif (preg_match('<^/' . $langHandler . 'organisations.json$>', $this->pageURL, $matches)) {
            $this->organisationsJsonPage($matches);


        } elseif (preg_match('<^/' . $langHandler . 'my-profile$>', $this->pageURL, $matches)) {
            $this->myProfilePage($matches);

        } elseif ($this->pageURL === '/my-profile.json') {
            $this->myProfilePage([], 'json');

        } elseif ($this->pageURL === '/createProject.json') {
            $this->createProjectPage();

        } elseif ($this->pageURL === '/createOrganisation.json') {
            $this->createOrganisationPage();

        } elseif ($this->pageURL === '/uploadProfilePicture') {
            $this->uploadProfilePicturePage();

        } elseif (preg_match('<^/' . $langHandler . 'personal-details$>', $this->pageURL, $matches)) {
            $this->personalDetailsPage($matches);

        } elseif ($this->pageURL === '/skills.json') {
            $this->skillsListJsonPage();

        } elseif ($this->pageURL === '/users.json') {
            $this->usersListJsonPage();

        } elseif ($this->pageURL === '/tags-for-projects.json') {
            $this->tagsForProjectsListJsonPage();

        } elseif ($this->pageURL === '/tags-for-organisations.json') {
            $this->tagsForOrganisationsListJsonPage();

        } elseif ($this->pageURL === '/impact-tags.json') {
            $this->impactTagsListJsonPage();

        } elseif ($this->pageURL === '/languages.json') {
            $this->languagesListJsonPage();

        } elseif ($this->pageURL === '/countries.json') {
            $this->countriesListJsonPage();

        } elseif ($this->pageURL === '/organisations.json') {
            $this->organisationsListJsonPage();

        } elseif (preg_match('<^/' . $langHandler . 'feedback\.json$>', $this->pageURL, $matches)) {
            $this->feedbackJsonPage($matches);

        } elseif (preg_match('<^/' . $langHandler . 'feedback$>', $this->pageURL, $matches)) {
            $this->feedbackPage($matches);

        } elseif ($this->pageURL === '/temp-gallery.json') {
            $this->tempGalleryJsonPage();

// Blog
        } elseif (preg_match('<^/' . $langHandler . 'stories$>', $this->pageURL, $matches)) {
            $this->storiesPage($matches);
        } elseif (preg_match('<^/' . $langHandler . 'blog$>', $this->pageURL, $matches)) {
            $this->storiesPage($matches);

        } elseif (preg_match('<^/' . $langHandler . 'stories\.json$>', $this->pageURL, $matches)) {
            $this->storiesJsonPage($matches);
        } elseif (preg_match('<^/' . $langHandler . 'blog\.json$>', $this->pageURL, $matches)) {
            $this->storiesJsonPage($matches);

        } elseif ($this->pageURL === '/story/add' OR $this->pageURL === '/blog/add') {
            $this->addStoryPage();

        } elseif (preg_match('<^/' . $langHandler . 'story/([0-9]+)(\/.*)?$>', $this->pageURL, $matches)) {
            $this->storyPage($matches);
        } elseif (preg_match('<^/' . $langHandler . 'blog/([0-9]+)(\/.*)?$>', $this->pageURL, $matches)) {
            $this->storyPage($matches);

        } elseif (preg_match('<^/' . $langHandler . 'story/edit/([0-9]+)\.json$>', $this->pageURL, $matches)) {
            $this->editStoryPage($matches, 'json');
        } elseif (preg_match('<^/' . $langHandler . 'blog/edit/([0-9]+)\.json$>', $this->pageURL, $matches)) {
            $this->editStoryPage($matches, 'json');

        } elseif (preg_match('<^/' . $langHandler . 'story/edit/([0-9]+)$>', $this->pageURL, $matches)) {
            $this->editStoryPage($matches);
        } elseif (preg_match('<^/' . $langHandler . 'blog/edit/([0-9]+)$>', $this->pageURL, $matches)) {
            $this->editStoryPage($matches);

// Search
        } elseif (preg_match('<^/' . $langHandler . 'search\.json$>', $this->pageURL, $matches)) {
            $this->searchPage($matches, 'json');

        } elseif (preg_match('<^/' . $langHandler . 'search/(.*)$>', $this->pageURL, $matches)) {
            $this->searchPage($matches);

// Case Studies
        } elseif (preg_match('<^/' . $langHandler . 'case-study/add$>', $this->pageURL, $matches)) {
            $this->addCaseStudyPage($matches);

        } elseif (preg_match('<^/' . $langHandler . 'case-studies$>', $this->pageURL, $matches)) {
            $this->caseStudiesPage($matches);

        } elseif (preg_match('<^/' . $langHandler . 'case-studies\.json$>', $this->pageURL, $matches)) {
            $this->caseStudiesJsonPage($matches);

        } elseif (preg_match('<^/' . $langHandler . 'case-study/edit/([0-9]+)\.json$>', $this->pageURL, $matches)) {
            $this->editCaseStudyPage($matches, 'json');

        } elseif (preg_match('<^/' . $langHandler . 'case-study/edit/([0-9]+)$>', $this->pageURL, $matches)) {
            $this->editCaseStudyPage($matches);

        } elseif (preg_match('<^/' . $langHandler . 'case-study/([0-9]+)(\/.*)?$>', $this->pageURL, $matches)) {
            $this->caseStudyPage($matches);

// Organisations
        } elseif (preg_match('<^/' . $langHandler . 'org/edit/([0-9]+)\.json$>', $this->pageURL, $matches)) {
            $this->editOrganisationPage($matches, 'json');

        } elseif (preg_match('<^/' . $langHandler . 'org/edit/([0-9]+)$>', $this->pageURL, $matches)) {
            $this->editOrganisationPage($matches);

            //} elseif (preg_match('<^/' . $langHandler . 'org/([0-9]+)\.json$>', $this->pageURL, $matches)) {
            //    $this->organisationJsonPage($matches);

        } elseif (preg_match('<^/' . $langHandler . 'org/([0-9]+)(\/.*)?$>', $this->pageURL, $matches)) {
            $this->organisationPage($matches);

// Static pages
        } elseif ($this->pageURL === '/robots.txt') {
            $this->robotsTxtPage();

        } elseif (preg_match('<^/' . $langHandler . 'explore-dsi$>', $this->pageURL, $matches)) {
            $this->exploreDsiPage($matches);

        } elseif (preg_match('<^/' . $langHandler . 'terms-of-use$>', $this->pageURL, $matches)) {
            $this->termsOfUsePage($matches);

        } elseif (preg_match('<^/' . $langHandler . 'privacy-policy$>', $this->pageURL, $matches)) {
            $this->privacyPolicyPage($matches);

        } elseif (preg_match('<^/' . $langHandler . 'updates$>', $this->pageURL, $matches)) {
            $this->updatesPage($matches);

// Sitemap
        } elseif (preg_match('<^/' . $langHandler . 'sitemap\.xml$>', $this->pageURL, $matches)) {
            $this->sitemapXmlPage($matches);

// Test
        } elseif (preg_match('<^/(([a-z]{2})/)?test$>', $this->pageURL, $matches)) {
            $this->testPage($matches);

// Unfiltered
        } elseif (preg_match('<^/countryRegions/([0-9]+)\.json$>', $this->pageURL, $matches)) {
            $this->countryRegionsListJsonPage($matches);

        } elseif (preg_match('<^/' . $langHandler . 'profile/([a-zA-Z0-9\.]+)/details\.json$>', $this->pageURL, $matches)) {
            $this->userProfilePage($matches, 'json');

        } elseif (preg_match('<^/' . $langHandler . 'profile/([a-zA-Z0-9\.]+)$>', $this->pageURL, $matches)) {
            $this->userProfilePage($matches);

        } elseif (preg_match('<^/' . $langHandler . 'project/([0-9]+)\.json?$>', $this->pageURL, $matches)) {
            $this->projectJsonPage($matches);

        } elseif (preg_match('<^/' . $langHandler . 'project/edit/([0-9]+)\.json$>', $this->pageURL, $matches)) {
            $this->editProjectPage($matches, 'json');

        } elseif (preg_match('<^/' . $langHandler . 'project/edit/([0-9]+)$>', $this->pageURL, $matches)) {
            $this->editProjectPage($matches);

        } elseif (preg_match('<^/projectPost/([0-9]+)\.json?$>', $this->pageURL, $matches)) {
            $this->projectPostJsonPage($matches);

        } elseif (preg_match('<^/projectPostComment/([0-9]+)\.json?$>', $this->pageURL, $matches)) {
            $this->projectPostCommentJsonPage($matches);

        } elseif (preg_match('<^/' . $langHandler . 'project/([0-9]+)(\/.*)?$>', $this->pageURL, $matches)) {
            $this->projectPage($matches);

        } elseif (preg_match('<^/.*\.(gif|jpe?g|png|svg|js|css|map)$>', $this->pageURL)) {
            pr('not found');
            return $this->staticContent();

        } else {
            $this->notFound404Page();
        }

        return true;
    }

    private function homePage($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\HomeController();
        $command->format = 'html';
        $command->exec();
    }

    private function dashboardPage($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\DashboardController();
        $command->format = 'html';
        $command->exec();
    }

    private function dashboardJsonPage($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\DashboardController();
        $command->format = 'json';
        $command->exec();
    }

    private function appLoginPage($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\AppLoginController();
        $command->exec();
    }

    private function appRegisterUserPage($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\AppRegisterUserController();
        $command->exec();
    }

    private function loginJsonPage($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\LoginController();
        $command->responseFormat = 'json';
        $command->exec();
    }

    private function forgotPasswordJsonPage()
    {
        $command = new \DSI\Controller\ForgotPasswordController();
        $command->responseFormat = 'json';
        $command->exec();
    }

    private function facebookLoginPage()
    {
        $command = new \DSI\Controller\LoginFacebookController();
        $command->exec();
    }

    private function googleLoginPage()
    {
        $command = new \DSI\Controller\LoginGoogleController();
        $command->exec();
    }

    private function gitHubLoginPage()
    {
        $command = new \DSI\Controller\LoginGitHubController();
        $command->exec();
    }

    private function twitterLoginPage()
    {
        $command = new \DSI\Controller\LoginTwitterController();
        $command->exec();
    }

    private function registerJsonPage()
    {
        $command = new \DSI\Controller\RegisterController();
        $command->responseFormat = 'json';
        $command->exec();
    }

    private function setAdminPage()
    {
        $command = new \DSI\Controller\SetAdminController();
        $command->exec();
    }

    private function logoutPage($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\LogoutController();
        $command->exec();
    }

    private function storiesPage($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\StoriesController();
        $command->exec();
    }

    private function storiesJsonPage($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\StoriesController();
        $command->format = 'json';
        $command->exec();
    }

    private function addStoryPage()
    {
        $command = new \DSI\Controller\StoryAddController();
        $command->exec();
    }

    private function addCaseStudyPage($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\CaseStudyAddController();
        $command->exec();
    }

// Funding
    private function fundingPage($matches = [], $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\FundingController();
        $command->format = $format;
        $command->exec();
    }

    private function addFundingPage($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\FundingAddController();
        $command->exec();
    }

    private function editFundingPage($matches = [], $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\FundingEditController();
        $command->fundingID = $matches[3];
        $command->format = $format;
        $command->exec();
    }

// Events
    private function eventsPage($matches = [], $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\EventsController();
        $command->format = $format;
        $command->exec();
    }

    private function addEventPage($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\EventAddController();
        $command->exec();
    }


    private function projectsPage($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\ProjectsController();
        $command->exec();
    }

    private function projectsJsonPage($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\ProjectsController();
        $command->responseFormat = 'json';
        $command->exec();
    }

    private function organisationsPage($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\OrganisationsController();
        $command->exec();
    }

    private function organisationsJsonPage($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\OrganisationsController();
        $command->responseFormat = 'json';
        $command->exec();
    }

    private function myProfilePage($matches, $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\MyProfileController();
        $command->data()->format = $format;
        $command->exec();
    }

    /**
     * @param $matches
     * @param string $format
     */
    private function userProfilePage($matches, $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\ProfileController();
        $command->data()->format = $format;
        $command->data()->userURL = $matches[3];
        $command->exec();
    }

    /**
     * @return bool
     */
    private function staticContent()
    {
        return false;
    }

    private function notFound404Page()
    {
        $command = new \DSI\Controller\NotFound404Controller();
        $command->exec();
    }

    private function skillsListJsonPage()
    {
        $command = new \DSI\Controller\ListSkillsController();
        $command->exec();
    }

    private function languagesListJsonPage()
    {
        $command = new \DSI\Controller\ListLanguagesController();
        $command->exec();
    }

    private function personalDetailsPage($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\PersonalDetailsController();
        $command->exec();
    }

    private function uploadProfilePicturePage()
    {
        $command = new \DSI\Controller\PersonalDetailsController();
        $command->exec();
    }

    private function createProjectPage()
    {
        $command = new \DSI\Controller\CreateProjectController();
        $command->exec();
    }

    private function createOrganisationPage()
    {
        $command = new \DSI\Controller\CreateOrganisationController();
        $command->exec();
    }

    private function projectPage($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\ProjectController();
        $command->data()->projectID = $matches[3];
        $command->exec();
    }

    private function caseStudyPage($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\CaseStudyController();
        $command->caseStudyID = $matches[3];
        $command->exec();
    }

    private function storyPage($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\StoryController();
        $command->data()->storyID = $matches[3];
        $command->exec();
    }

    private function editStoryPage($matches, $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\StoryEditController();
        $command->storyID = $matches[3];
        $command->format = $format;
        $command->exec();
    }

    private function organisationPage($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\OrganisationController();
        $command->data()->organisationID = $matches[3];
        $command->exec();
    }

    private function projectJsonPage($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\ProjectController();
        $command->data()->projectID = $matches[3];
        $command->data()->format = 'json';
        $command->exec();
    }

    private function editProjectPage($matches, $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\ProjectEditController();
        $command->projectID = $matches[3];
        $command->format = $format;
        $command->exec();
    }

    private function editOrganisationPage($matches, $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\OrganisationEditController();
        $command->organisationID = $matches[3];
        $command->format = $format;
        $command->exec();
    }

    private function editCaseStudyPage($matches, $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\CaseStudyEditController();
        $command->caseStudyID = $matches[3];
        $command->format = $format;
        $command->exec();
    }

    private function searchPage($matches, $format = 'html')
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\SearchController();
        $command->term = $matches[3] ?? '';
        $command->format = $format;
        $command->exec();
    }

    private function caseStudiesPage($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\CaseStudiesController();
        $command->exec();
    }

    private function caseStudiesJsonPage($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\CaseStudiesController();
        $command->format = 'json';
        $command->exec();
    }

    private function projectPostJsonPage($matches)
    {
        $command = new \DSI\Controller\ProjectPostController();
        $command->data()->postID = $matches[1];
        $command->data()->format = 'json';
        $command->exec();
    }

    private function projectPostCommentJsonPage($matches)
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

    private function tagsForProjectsListJsonPage()
    {
        $command = new \DSI\Controller\ListTagsForProjectsController();
        $command->exec();
    }

    private function tagsForOrganisationsListJsonPage()
    {
        $command = new \DSI\Controller\ListTagsForOrganisationsController();
        $command->exec();
    }

    private function impactTagsListJsonPage()
    {
        $command = new \DSI\Controller\ListImpactTagsController();
        $command->exec();
    }

    private function usersListJsonPage()
    {
        $command = new \DSI\Controller\ListUsersController();
        $command->exec();
    }

    private function countriesListJsonPage()
    {
        $command = new \DSI\Controller\ListCountriesController();
        $command->exec();
    }

    private function organisationsListJsonPage()
    {
        $command = new \DSI\Controller\ListOrganisationsController();
        $command->exec();
    }

    private function countryRegionsListJsonPage($matches)
    {
        $command = new \DSI\Controller\ListCountryRegionsController();
        $command->data()->countryID = $matches[1];
        $command->exec();
    }

    private function feedbackPage($matches = [])
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\FeedbackController();
        $command->exec();
    }

    private function feedbackJsonPage($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\FeedbackController();
        $command->format = 'json';
        $command->exec();
    }

    private function robotsTxtPage()
    {
        $command = new \DSI\Controller\StaticHtmlController();
        $command->format = 'txt';
        $command->view = 'robots.txt.php';
        $command->exec();
    }

    private function exploreDsiPage($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\StaticHtmlController();
        $command->view = 'explore-dsi.php';
        $command->exec();
    }

    private function termsOfUsePage($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\StaticHtmlController();
        $command->view = 'terms-of-use.php';
        $command->exec();
    }

    private function privacyPolicyPage($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\StaticHtmlController();
        $command->view = 'privacy-policy.php';
        $command->exec();
    }

    private function updatesPage($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\StaticHtmlController();
        $command->view = 'updates.php';
        $command->exec();
    }

    private function testPage($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\TestController();
        $command->exec();
    }

    private function sitemapXmlPage($matches)
    {
        $this->setLanguageFromUrl($matches);

        $command = new \DSI\Controller\SitemapController();
        $command->format = 'xml';
        $command->exec();
    }

    private function tempGalleryJsonPage()
    {
        $command = new \DSI\Controller\TempGalleryController();
        $command->format = 'json';
        $command->exec();
    }

    private function importPage()
    {
        $command = new \DSI\Controller\ImportController();
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

$pageURL =
    $_SERVER['PATH_INFO'] ??
    explode('?', $_SERVER['REQUEST_URI'], 2)[0] ??
    '/';
// pr($pageURL);

if (substr($pageURL, 0, strlen(SITE_RELATIVE_PATH)) == SITE_RELATIVE_PATH) {
    $pageURL = substr($pageURL, strlen(SITE_RELATIVE_PATH));
}

$router = new Router();
if (MUST_USE_HTTPS)
    $router->forceHTTPS();
return $router->exec($pageURL);