<?php

namespace DSI\Controller;

use DSI\Repository\LanguageRepository;
use DSI\Repository\OrganisationMemberRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\OrganisationRepositoryInAPC;
use DSI\Repository\ProjectMemberRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\ProjectRepositoryInAPC;
use DSI\Repository\SkillRepository;
use DSI\Repository\UserLanguageRepository;
use DSI\Repository\UserRepository;
use DSI\Repository\UserSkillRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class PersonalDetailsController
{
    public function __construct()
    {
    }

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());
        $loggedInUser = $authUser->getUser();

        $languages = (new LanguageRepository())->getAll();
        $userLanguages = (new UserLanguageRepository())->getLanguageIDsForUser($loggedInUser->getId());
        $skills = (new SkillRepository())->getAll();
        $userSkills = (new UserSkillRepository())->getSkillsNameByUserID($loggedInUser->getId());
        $projects = (new ProjectRepositoryInAPC())->getAll();
        $userProjects = (new ProjectMemberRepository())->getProjectIDsForMember($loggedInUser->getId());
        $organisations = (new OrganisationRepositoryInAPC())->getAll();
        $userOrganisations = (new OrganisationMemberRepository())->getOrganisationIDsForMember($loggedInUser->getId());

        $angularModules['fileUpload'] = true;
        require __DIR__ . '/../../../www/personal-details.php';
    }
}