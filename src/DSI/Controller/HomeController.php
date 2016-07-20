<?php

namespace DSI\Controller;

use DSI\Entity\OrganisationMemberInvitation;
use DSI\Entity\ProjectMemberInvitation;
use DSI\Repository\OrganisationMemberInvitationRepository;
use DSI\Repository\OrganisationMemberRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\ProjectMemberInvitationRepository;
use DSI\Repository\ProjectMemberRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\StoryRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\ApproveMemberInvitationToOrganisation;
use DSI\UseCase\ApproveMemberInvitationToProject;
use DSI\UseCase\RejectMemberInvitationToOrganisation;
use DSI\UseCase\RejectMemberInvitationToProject;

class HomeController
{
    public $format = 'html';

    public function exec()
    {
        $authUser = new Auth();

        if ($authUser->isLoggedIn())
            $loggedInUser = (new UserRepository())->getById($authUser->getUserId());
        else
            $loggedInUser = null;

        $hideSearch = true;
        $stories = (new StoryRepository())->getPublishedLast(3);
        require __DIR__ . '/../../../www/home.php';
    }
}