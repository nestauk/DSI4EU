<?php

namespace DSI\Controller\CLI;

set_time_limit(0);

use DSI\Entity\Organisation;
use DSI\Entity\Project;
use DSI\Entity\User;
use DSI\NotFound;
use DSI\Repository\OrganisationProjectRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\UserRepository;
use DSI\UseCase\AddProjectToOrganisation;
use DSI\UseCase\SetAdminStatusToOrganisationMember;
use DSI\UseCase\SetAdminStatusToProjectMember;

class ImportUserProjectOrganisationLinkController
{
    private $sysAdminUser;

    public function exec()
    {
        $this->sysAdminUser = new User;
        $this->sysAdminUser->setRole('sys-admin');

        $this->importLinksFrom(__DIR__ . '/../../../import/dsi-users-organisations-projects.csv');
    }

    /**
     * @param $file
     */
    private function importLinksFrom($file)
    {
        $row = 0;
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle)) !== FALSE) {
                $row++;
                if ($row === 1) {
                    continue;
                }

                $this->importRow($data);
            }
            fclose($handle);
        }
    }

    /**
     * @param $data
     */
    private function importRow($data)
    {
        $email = $data[0];
        $organisationUID = $data[1];
        $projectUID = $data[4];

        if($organisationUID == 'nan')
            $organisationUID = '';
        if(!$organisationUID AND !$projectUID)
            return;
        if($email == 'samuel@.iv.io')
            return;

        /*
        print_r([
            'email' => $email,
            'organisationUID' => $organisationUID,
            'projectUID' => $projectUID,
        ]);
        */

        try {
            $user = (new UserRepository())->getByEmail($email);
        } catch(NotFound $e){
            $user = new User();
            $user->setEmail($email);
            (new UserRepository())->insert($user);
        }

        try{
            $organisation = (new OrganisationRepository())->getByImportID($organisationUID);
        } catch (NotFound $e){
            return;
        }

        try{
            $project = (new ProjectRepository())->getByImportID($projectUID);
        } catch (NotFound $e){
            return;
        }

        $this->organisationMustHaveProject($organisation, $project);
        $this->addAdminToProject($project, $user);
        $this->addAdminToOrganisation($organisation, $user);
    }

    /**
     * @return User
     */
    private function setOwner()
    {
        // TODO create root user
        $owner = new User();
        $owner->setId(1);
        return $owner;
    }

    private function addAdminToProject(Project $project, User $user)
    {
        $projectAdmin = new SetAdminStatusToProjectMember();
        $projectAdmin->setProject($project);
        $projectAdmin->setExecutor($this->sysAdminUser);
        $projectAdmin->setMember($user);
        $projectAdmin->setIsAdmin(true);
        $projectAdmin->exec();
    }

    private function addAdminToOrganisation(Organisation $organisation, User $user)
    {
        $organisationAdmin = new SetAdminStatusToOrganisationMember();
        $organisationAdmin->setOrganisation($organisation);
        $organisationAdmin->setExecutor($this->sysAdminUser);
        $organisationAdmin->setMember($user);
        $organisationAdmin->setIsAdmin(true);
        $organisationAdmin->exec();
    }

    private function organisationMustHaveProject(Organisation $organisation, Project $project)
    {
        if (!(new OrganisationProjectRepository())->organisationHasProject(
            $organisation->getId(),
            $project->getId()
        )
        ) {
            $addProjectToOrg = new AddProjectToOrganisation();
            $addProjectToOrg->data()->organisationID = $organisation->getId();
            $addProjectToOrg->data()->projectID = $project->getId();
            $addProjectToOrg->exec();
        }
    }
}