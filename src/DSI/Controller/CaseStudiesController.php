<?php

namespace DSI\Controller;

use DSI\Repository\CaseStudyRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;

class CaseStudiesController
{
    /** @var string */
    public $format = 'html';

    public function exec()
    {
        $authUser = new Auth();
        if ($authUser->isLoggedIn())
            $loggedInUser = (new UserRepository())->getById($authUser->getUserId());

        if ($this->format == 'json') {

        } else {
            $caseStudies = (new CaseStudyRepository())->getAll();
            require __DIR__ . '/../../../www/case-studies.php';
        }
    }
}