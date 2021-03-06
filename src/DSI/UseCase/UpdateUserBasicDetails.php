<?php

namespace DSI\UseCase;

use DSI\Repository\LanguageRepo;
use DSI\Repository\OrganisationMemberRepo;
use DSI\Repository\OrganisationMemberRequestRepo;
use DSI\Repository\ProjectMemberRepo;
use DSI\Repository\ProjectMemberRequestRepo;
use DSI\Repository\UserLanguageRepo;
use DSI\Repository\UserLinkRepo;
use DSI\Repository\UserRepo;
use DSI\Repository\UserSkillRepo;
use DSI\Service\ErrorHandler;

class UpdateUserBasicDetails
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UpdateUserBasicDetails_Data */
    private $data;

    /** @var UserRepo */
    private $userRepo;

    public function __construct()
    {
        $this->data = new UpdateUserBasicDetails_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->userRepo = new UserRepo();

        if (isset($this->data()->firstName)) {
            if ($this->data()->firstName == '') {
                $this->errorHandler->addTaggedError('firstName', __('Please type the first name'));
            }
        }
        if (isset($this->data()->lastName)) {
            if ($this->data()->lastName == '') {
                $this->errorHandler->addTaggedError('lastName', __('Please type the last name'));
            }
        }
        if (isset($this->data()->bio)) {
            if (strlen($this->data()->bio) > 140) {
                $this->errorHandler->addTaggedError('bio', __('Please submit a shorter bio'));
            }
        }

        $this->errorHandler->throwIfNotEmpty();

        $this->saveUserDetails();
    }

    /**
     * @return UpdateUserBasicDetails_Data
     */
    public function data()
    {
        return $this->data;
    }

    private function saveUserDetails()
    {
        $user = $this->userRepo->getById($this->data()->userID);
        if (isset($this->data()->firstName))
            $user->setFirstName($this->data()->firstName);
        if (isset($this->data()->lastName))
            $user->setLastName($this->data()->lastName);
        if (isset($this->data()->showEmail))
            $user->setShowEmail($this->data()->showEmail);
        if (isset($this->data()->cityName))
            $user->setCityName($this->data()->cityName);
        if (isset($this->data()->countryName))
            $user->setCountryName($this->data()->countryName);
        if (isset($this->data()->jobTitle))
            $user->setJobTitle($this->data()->jobTitle);
        if (isset($this->data()->company))
            $user->setCompany($this->data()->company);
        if (isset($this->data()->bio))
            $user->setBio($this->data()->bio);
        if (isset($this->data()->languages))
            $this->setUserLanguages();
        if (isset($this->data()->skills))
            $this->setUserSkills();
        if (isset($this->data()->links))
            $this->setUserLinks();
        if (isset($this->data()->projects))
            $this->setUserProjects();
        if (isset($this->data()->organisations))
            $this->setUserOrganisations();

        $this->userRepo->save($user);
    }

    private function setUserLanguages()
    {
        $userLanguages = (new UserLanguageRepo())->getLanguageIDsForUser($this->data()->userID);
        foreach ($this->data()->languages AS $newLang) {
            if (!in_array($newLang, $userLanguages)) {
                $addLang = new AddLanguageToUser();
                $addLang->data()->userID = $this->data()->userID;
                $addLang->data()->language = (new LanguageRepo())->getById($newLang)->getName();
                $addLang->exec();
            }
        }
        foreach ($userLanguages AS $oldLang) {
            if (!in_array($oldLang, $this->data()->languages)) {
                $remLang = new RemoveLanguageFromUser();
                $remLang->data()->userID = $this->data()->userID;
                $remLang->data()->language = (new LanguageRepo())->getById($oldLang)->getName();
                $remLang->exec();
            }
        }
    }

    private function setUserSkills()
    {
        $userSkills = (new UserSkillRepo())->getSkillsNameByUserID($this->data()->userID);
        foreach ($this->data()->skills AS $newSkillName) {
            if (!in_array($newSkillName, $userSkills)) {
                $addSkill = new AddSkillToUser();
                $addSkill->data()->userID = $this->data()->userID;
                $addSkill->data()->skill = $newSkillName;
                $addSkill->exec();
            }
        }
        foreach ($userSkills AS $oldSkillName) {
            if (!in_array($oldSkillName, $this->data()->skills)) {
                $remSkill = new RemoveSkillFromUser();
                $remSkill->data()->userID = $this->data()->userID;
                $remSkill->data()->skill = $oldSkillName;
                $remSkill->exec();
            }
        }
    }

    private function setUserLinks()
    {
        $userLinks = (new UserLinkRepo())->getLinksByUserID($this->data()->userID);
        $this->data()->links = array_filter((array)$this->data()->links);
        foreach ((array)$this->data()->links AS $newLink) {
            if (!in_array($newLink, $userLinks)) {
                $addLink = new AddLinkToUser();
                $addLink->data()->userID = $this->data()->userID;
                $addLink->data()->link = $newLink;
                $addLink->exec();
            }
        }
        foreach ((array)$userLinks AS $oldLink) {
            if (!in_array($oldLink, $this->data()->links)) {
                $remLink = new RemoveLinkFromUser();
                $remLink->data()->userID = $this->data()->userID;
                $remLink->data()->link = $oldLink;
                $remLink->exec();
            }
        }
    }

    private function setUserProjects()
    {
        $userProjects = (new ProjectMemberRepo())->getProjectIDsForMember($this->data()->userID);
        $userProjectRequests = (new ProjectMemberRequestRepo())->getProjectIDsForMember($this->data()->userID);
        foreach ($this->data()->projects AS $newProjectID) {
            if (!in_array($newProjectID, $userProjects) AND !in_array($newProjectID, $userProjectRequests)) {
                $addMemberRequest = new AddMemberRequestToProject();
                $addMemberRequest->data()->userID = $this->data()->userID;
                $addMemberRequest->data()->projectID = $newProjectID;
                $addMemberRequest->exec();
            }
        }
        foreach ($userProjects AS $currentProjectID) {
            if (!in_array($currentProjectID, $this->data()->projects)) {
                $remMember = new RemoveMemberFromProject();
                $remMember->setUserId($this->data()->userID);
                $remMember->setProjectId($currentProjectID);
                $remMember->exec();
            }
        }
    }

    private function setUserOrganisations()
    {
        $userOrganisations = (new OrganisationMemberRepo())->getOrganisationIDsForMember($this->data()->userID);
        $userOrganisationRequests = (new OrganisationMemberRequestRepo())->getOrganisationIDsForMember($this->data()->userID);
        foreach ($this->data()->organisations AS $newOrgID) {
            if (!in_array($newOrgID, $userOrganisations) AND !in_array($newOrgID, $userOrganisationRequests)) {
                $addMemberRequest = new AddMemberRequestToOrganisation();
                $addMemberRequest->data()->userID = $this->data()->userID;
                $addMemberRequest->data()->organisationID = $newOrgID;
                $addMemberRequest->exec();
            }
        }
        foreach ($userOrganisations AS $currentOrgID) {
            if (!in_array($currentOrgID, $this->data()->organisations)) {
                $remMember = new RemoveMemberFromOrganisation();
                $remMember->setUserID($this->data()->userID);
                $remMember->setOrganisationID($currentOrgID);
                $remMember->exec();
            }
        }
    }
}

class UpdateUserBasicDetails_Data
{
    /** @var string */
    public $firstName,
        $lastName,
        $showEmail,
        $cityName,
        $countryName,
        $jobTitle,
        $company,
        $bio;

    /** @var bool */
    public $isAdmin,
        $isSuperAdmin;

    /** @var int */
    public $userID;

    /** @var int[] */
    public $languages,
        $projects,
        $organisations;

    /** @var string[] */
    public $skills,
        $links;
}