<?php

namespace DSI\Controller;

use DSI\Entity\Project;
use DSI\Entity\ProjectMember;
use DSI\Entity\User;
use DSI\Repository\ProjectMemberRepository;
use DSI\Repository\ProjectRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;

class ProjectEditMembersController
{
    /** @var  int */
    public $projectID;

    /** @var String */
    public $format = 'html';

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());
        $loggedInUser = $authUser->getUser();

        $projectRepository = new ProjectRepository();
        $project = $projectRepository->getById($this->projectID);
        $owner = $project->getOwner();

        $members = (new ProjectMemberRepository())->getByProject($project);
        $isOwner = $this->isOwner($project, $loggedInUser);
        $isAdmin = $this->isAdmin($members, $loggedInUser);

        if (!$isOwner AND !$isAdmin AND !$loggedInUser->isSysAdmin())
            go_to($urlHandler->home());

        if (isset($_POST['save'])) {
            try {
                /*
                $exec = new ChangeOwner();
                $exec->data()->executor = $loggedInUser;
                $exec->data()->member = (new UserRepository())->getById($_POST['newOwnerID']);
                $exec->data()->project = $project;
                $exec->exec();

                echo json_encode([
                    'code' => 'ok',
                    'url' => $urlHandler->project($project),
                ]);
                */
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors(),
                ]);
            }
            return;
        }

        if ($this->format == 'json') {
            echo json_encode([
                'members' => array_map(function (ProjectMember $member) use ($project) {
                    $user = $member->getMember();
                    return [
                        'id' => $user->getId(),
                        'name' => $user->getFullName(),
                        'jobTitle' => $user->getJobTitle(),
                        'profilePic' => $user->getProfilePic(),
                        'isAdmin' => $member->isAdmin(),
                        'isOwner' => $member->getMemberID() == $project->getOwnerID(),
                    ];
                }, $members),
            ]);
        } else {
            $pageTitle = $project->getName();
            require __DIR__ . '/../../../www/views/project-edit-members.php';
        }
    }

    /**
     * @param Project $project
     * @param User $user
     * @return bool
     */
    private function isOwner(Project $project, User $user): bool
    {
        return $project->getOwner()->getId() == $user->getId();
    }

    /**
     * @param ProjectMember[] $members
     * @param User $user
     * @return bool
     */
    private function isAdmin($members, User $user): bool
    {
        foreach ($members AS $member) {
            if (
                ($member->getMemberID() == $user->getId())
                AND
                ($member->isAdmin())
            )
                return true;
        }

        return false;
    }
}