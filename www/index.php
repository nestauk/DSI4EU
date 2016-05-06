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

        } elseif ($this->pageURL === '/login.json') {
            $this->loginJsonPage();

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

        } elseif ($this->pageURL === '/projects') {
            $this->projectsPage();

        } elseif ($this->pageURL === '/organisations') {
            $this->organisationsPage();

        } elseif ($this->pageURL === '/my-profile') {
            $this->myProfilePage();

        } elseif ($this->pageURL === '/my-profile.json') {
            $this->myProfilePage('json');

        } elseif ($this->pageURL === '/uploadProfilePicture') {
            $this->uploadProfilePicturePage();

        } elseif ($this->pageURL === '/personal-details') {
            $this->personalDetailsPage();

        } elseif ($this->pageURL === '/skills.json') {
            $this->skillsListJsonPage();

        } elseif ($this->pageURL === '/languages.json') {
            $this->languagesListJsonPage();

        } elseif (preg_match('<^/profile/([a-zA-Z0-9\.]*)/details\.json$>', $this->pageURL, $matches)) {
            $this->userProfilePage($matches, 'json');

        } elseif (preg_match('<^/profile/([a-zA-Z0-9\.]*)$>', $this->pageURL, $matches)) {
            $this->userProfilePage($matches);

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

    private function projectsPage()
    {
        $command = new \DSI\Controller\ProjectsController();
        $command->exec();
    }

    private function organisationsPage()
    {
        $command = new \DSI\Controller\OrganisationsController();
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