<?php

namespace DSI\Controller;

use DSI\Entity\User;
use DSI\Service\Auth;
use DSI\Service\Translate;
use Services\View;

class StaticHtmlController
{
    public $format = 'html';
    public $view;

    /** @var User */
    private $loggedInUser;

    public function __construct()
    {
        $authUser = new Auth();
        $this->loggedInUser = $authUser->getUserIfLoggedIn();
    }

    public function exec()
    {
        if ($this->format == 'txt')
            header("Content-Type: text/plain");


        $loggedInUser = $this->loggedInUser;
        require __DIR__ . '/../../../www/views/' . $this->view;
    }

    public function contact()
    {
        View::setPageTitle('Contact - DSI4EU');
        return View::render(__DIR__ . '/../../../www/views/contact-dsi.php', [
            'loggedInUser' => $this->loggedInUser
        ]);
    }

    public function about()
    {
        View::setPageTitle('About the project - DSI4EU');
        View::setPageDescription(__('Find out more about our work supporting digital social innovation, tech for good and civic tech to grow and scale in Europe.'));
        if (Translate::getCurrentLang() === 'en') {
            return View::render(__DIR__ . '/../../../www/views/about-the-project_en.php', [
                'loggedInUser' => $this->loggedInUser
            ]);
        } else {
            return View::render(__DIR__ . '/../../../www/views/about-the-project.php', [
                'loggedInUser' => $this->loggedInUser
            ]);
        }
    }
}