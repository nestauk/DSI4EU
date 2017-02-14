<?php

namespace DSI\Controller;

use DSI\Repository\OrganisationRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\URL;
use DSI\UseCase\SetAdminStatusToOrganisationMember;
use DSI\UseCase\SetAdminStatusToProjectMember;

class SetAdminController
{
    public function exec()
    {
        $urlHandler = new URL();

        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->home());
        $loggedInUser = $authUser->getUser();

        if (!$loggedInUser->isSysAdmin())
            go_to($urlHandler->home());

        if (isset($_GET['userID'])) {
            if (isset($_GET['projectID'])) {
                $projectRepo = new ProjectRepository();
                $project = $projectRepo->getById((int)$_GET['projectID']);
                $user = (new UserRepository())->getById((int)$_GET['userID']);

                $exec = new SetAdminStatusToProjectMember();
                $exec->setMember($user);
                $exec->setProject($project);
                $exec->setIsAdmin(true);
                $exec->setExecutor($loggedInUser);
                $exec->exec();

                if (isset($_GET['owner'])) {
                    $project->setOwner($user);
                    $projectRepo->save($project);
                    echo 'Project Owner set';
                } else {
                    echo 'Project Admin set';
                }
            }
            if (isset($_GET['organisationID'])) {
                $user = (new UserRepository())->getById((int)$_GET['userID']);
                $organisationRepository = new OrganisationRepository();
                $organisation = $organisationRepository->getById((int)$_GET['organisationID']);

                $exec = new SetAdminStatusToOrganisationMember();
                $exec->data()->member = $user;
                $exec->data()->organisation = $organisation;
                $exec->data()->isAdmin = true;
                $exec->data()->executor = $loggedInUser;
                $exec->exec();

                if (isset($_GET['owner'])) {
                    $organisation->setOwner($user);
                    $organisationRepository->save($organisation);
                    echo 'Organisation Owner set';
                } else {

                    echo 'Organisation Admin set';
                }
            }
        }
    }
}