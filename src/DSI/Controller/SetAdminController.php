<?php

namespace DSI\Controller;

use DSI\Repository\OrganisationRepo;
use DSI\Repository\OrganisationRepoInAPC;
use DSI\Repository\ProjectRepo;
use DSI\Repository\ProjectRepoInAPC;
use DSI\Repository\UserRepo;
use DSI\Service\Auth;
use Services\URL;
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
                $projectRepo = new ProjectRepoInAPC();
                $project = $projectRepo->getById((int)$_GET['projectID']);
                $user = (new UserRepo())->getById((int)$_GET['userID']);

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
                $user = (new UserRepo())->getById((int)$_GET['userID']);
                $organisationRepository = new OrganisationRepoInAPC();
                $organisation = $organisationRepository->getById((int)$_GET['organisationID']);

                $exec = new SetAdminStatusToOrganisationMember();
                $exec->setMember($user);
                $exec->setOrganisation($organisation);
                $exec->setExecutor($loggedInUser);
                $exec->setIsAdmin(true);
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