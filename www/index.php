<?php

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
        $this->pageURL = $pageURL;

        if ($this->pageURL === '/') {
            $this->homePage();

        } elseif ($this->pageURL === '/import') {
            $this->importPage();

        } elseif ($this->pageURL === '/login.json') {
            $this->loginJsonPage();

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

        } elseif ($this->pageURL === '/register.json') {
            $this->registerJsonPage();

        } elseif ($this->pageURL === '/login') {
            go_to(\DSI\Service\URL::home());

        } elseif ($this->pageURL === '/logout') {
            $this->logoutPage();

        } elseif ($this->pageURL === '/stories') {
            $this->storiesPage();

        } elseif ($this->pageURL === '/story/add') {
            $this->addStoryPage();

        } elseif ($this->pageURL === '/projects') {
            $this->projectsPage();

        } elseif ($this->pageURL === '/projects.json') {
            $this->projectsJsonPage();

        } elseif ($this->pageURL === '/organisations') {
            $this->organisationsPage();

        } elseif ($this->pageURL === '/organisations.json') {
            $this->organisationsJsonPage();

        } elseif ($this->pageURL === '/my-profile') {
            $this->myProfilePage();

        } elseif ($this->pageURL === '/my-profile.json') {
            $this->myProfilePage('json');

        } elseif ($this->pageURL === '/createProject.json') {
            $this->createProjectPage();

        } elseif ($this->pageURL === '/createOrganisation.json') {
            $this->createOrganisationPage();

        } elseif ($this->pageURL === '/uploadProfilePicture') {
            $this->uploadProfilePicturePage();

        } elseif ($this->pageURL === '/personal-details') {
            $this->personalDetailsPage();

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

        } elseif ($this->pageURL === '/feedback') {
            $this->feedbackPage();

        } elseif ($this->pageURL === '/temp-gallery.json') {
            $this->tempGalleryJsonPage();

        } elseif ($this->pageURL === '/feedback.json') {
            $this->feedbackJsonPage();

        } elseif (preg_match('<^/countryRegions/([0-9]+)\.json$>', $this->pageURL, $matches)) {
            $this->countryRegionsListJsonPage($matches);

        } elseif (preg_match('<^/profile/([a-zA-Z0-9\.]+)/details\.json$>', $this->pageURL, $matches)) {
            $this->userProfilePage($matches, 'json');

        } elseif (preg_match('<^/profile/([a-zA-Z0-9\.]+)$>', $this->pageURL, $matches)) {
            $this->userProfilePage($matches);

        } elseif (preg_match('<^/project/([0-9]+)\.json?$>', $this->pageURL, $matches)) {
            $this->projectJsonPage($matches);

        } elseif (preg_match('<^/projectPost/([0-9]+)\.json?$>', $this->pageURL, $matches)) {
            $this->projectPostJsonPage($matches);

        } elseif (preg_match('<^/projectPostComment/([0-9]+)\.json?$>', $this->pageURL, $matches)) {
            $this->projectPostCommentJsonPage($matches);

        } elseif (preg_match('<^/project/([0-9]+)(\/.*)?$>', $this->pageURL, $matches)) {
            $this->projectPage($matches);

        } elseif (preg_match('<^/story/([0-9]+)(\/.*)?$>', $this->pageURL, $matches)) {
            $this->storyPage($matches);

        } elseif (preg_match('<^/story/edit/([0-9]+)\.json$>', $this->pageURL, $matches)) {
            $this->editStoryPage($matches, 'json');

        } elseif (preg_match('<^/story/edit/([0-9]+)$>', $this->pageURL, $matches)) {
            $this->editStoryPage($matches);

        } elseif (preg_match('<^/org/([0-9]+)\.json?$>', $this->pageURL, $matches)) {
            $this->organisationJsonPage($matches);

        } elseif (preg_match('<^/org/([0-9]+)(\/.*)?$>', $this->pageURL, $matches)) {
            $this->organisationPage($matches);

        } elseif (preg_match('<^/.*\.(gif|jpe?g|png|svg|js|css|map)$>', $this->pageURL)) {
            pr('not found');
            return $this->staticContent();

        } else {
            $this->notFound404Page();

        }

        return true;
    }

    private function homePage()
    {
        $command = new \DSI\Controller\HomeController();
        $command->exec();
    }

    private function loginJsonPage()
    {
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

    private function logoutPage()
    {
        $command = new \DSI\Controller\LogoutController();
        $command->exec();
    }

    private function storiesPage()
    {
        $command = new \DSI\Controller\StoriesController();
        $command->exec();
    }

    private function addStoryPage()
    {
        $command = new \DSI\Controller\StoryAddController();
        $command->exec();
    }

    private function projectsPage()
    {
        $command = new \DSI\Controller\ProjectsController();
        $command->exec();
    }

    private function projectsJsonPage()
    {
        $command = new \DSI\Controller\ProjectsController();
        $command->responseFormat = 'json';
        $command->exec();
    }

    private function organisationsPage()
    {
        $command = new \DSI\Controller\OrganisationsController();
        $command->exec();
    }

    private function organisationsJsonPage()
    {
        $command = new \DSI\Controller\OrganisationsController();
        $command->responseFormat = 'json';
        $command->exec();
    }

    private function myProfilePage($format = 'html')
    {
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
        $command = new \DSI\Controller\ProfileController();
        $command->data()->format = $format;
        $command->data()->userURL = $matches[1];
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
        echo $this->pageURL . ': 404 not found';
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

    private function personalDetailsPage()
    {
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
        $command = new \DSI\Controller\ProjectController();
        $command->data()->projectID = $matches[1];
        $command->exec();
    }

    private function storyPage($matches)
    {
        $command = new \DSI\Controller\StoryController();
        $command->data()->storyID = $matches[1];
        $command->exec();
    }

    private function editStoryPage($matches, $format = 'html')
    {
        $command = new \DSI\Controller\StoryEditController();
        $command->storyID = $matches[1];
        $command->format = $format;
        $command->exec();
    }

    private function organisationPage($matches)
    {
        $command = new \DSI\Controller\OrganisationController();
        $command->data()->organisationID = $matches[1];
        $command->exec();
    }

    private function projectJsonPage($matches)
    {
        $command = new \DSI\Controller\ProjectController();
        $command->data()->projectID = $matches[1];
        $command->data()->format = 'json';
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

    private function organisationJsonPage($matches)
    {
        $command = new \DSI\Controller\OrganisationController();
        $command->data()->organisationID = $matches[1];
        $command->data()->format = 'json';
        $command->exec();
    }

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

    private function feedbackPage()
    {
        $command = new \DSI\Controller\FeedbackController();
        $command->exec();
    }

    private function feedbackJsonPage()
    {
        $command = new \DSI\Controller\FeedbackController();
        $command->format = 'json';
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
return $router->exec($pageURL);