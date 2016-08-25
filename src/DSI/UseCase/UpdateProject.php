<?php

namespace DSI\UseCase;

use abeautifulsite\SimpleImage;
use DSI\Entity\Image;
use DSI\Entity\Project;
use DSI\Entity\User;
use DSI\NotEnoughData;
use DSI\NotFound;
use DSI\Repository\CountryRegionRepository;
use DSI\Repository\OrganisationProjectRepository;
use DSI\Repository\ProjectImpactTagARepository;
use DSI\Repository\ProjectDsiFocusTagRepository;
use DSI\Repository\ProjectImpactTagCRepository;
use DSI\Repository\ProjectLinkRepository;
use DSI\Repository\ProjectMemberRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\ProjectTagRepository;
use DSI\Service\ErrorHandler;

class UpdateProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UpdateProject_Data */
    private $data;

    /** @var ProjectRepository */
    private $projectRepo;

    public function __construct()
    {
        $this->data = new UpdateProject_Data();
        $this->projectRepo = new ProjectRepository();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();

        $this->checkIfAllInfoHaveBeenSent();
        $this->checkIfUserCanEditTheProject();
        $this->checkIfNameIsNotEmpty();
        $this->saveProjectDetails();
        $this->updateInvolvedOrganisationCount();
    }

    /**
     * @return UpdateProject_Data
     */
    public function data()
    {
        return $this->data;
    }

    private function saveProjectDetails()
    {
        if (isset($this->data()->name))
            $this->data()->project->setName($this->data()->name);
        if (isset($this->data()->shortDescription))
            $this->data()->project->setShortDescription($this->data()->shortDescription);
        if (isset($this->data()->description))
            $this->data()->project->setDescription($this->data()->description);
        if (isset($this->data()->socialImpact))
            $this->data()->project->setSocialImpact($this->data()->socialImpact);
        if (isset($this->data()->url))
            $this->data()->project->setUrl($this->data()->url);
        if (isset($this->data()->status))
            $this->data()->project->setStatus($this->data()->status);
        if (isset($this->data()->startDate))
            $this->data()->project->setStartDate($this->data()->startDate);
        if (isset($this->data()->endDate))
            $this->data()->project->setEndDate($this->data()->endDate);
        if (isset($this->data()->countryID) AND isset($this->data()->region))
            $this->setRegion();

        $this->projectRepo->save($this->data()->project);

        if (isset($this->data()->tags))
            $this->setTags();
        if (isset($this->data()->impactTagsA))
            $this->setImpactTagsA();
        if (isset($this->data()->impactTagsB))
            $this->setImpactTagsB();
        if (isset($this->data()->impactTagsC))
            $this->setImpactTagsC();
        if (isset($this->data()->links))
            $this->setLinks();
        if (isset($this->data()->organisations))
            $this->setOrganisations();

        $this->saveImages();
    }

    private function updateInvolvedOrganisationCount()
    {
        $organisationsCount = count((new OrganisationProjectRepository())->getByProjectID($this->data()->project->getId()));
        $this->data()->project->setOrganisationsCount($organisationsCount);
        $this->projectRepo->save($this->data()->project);
    }

    private function setTags()
    {
        $projectTags = (new ProjectTagRepository())->getTagsNameByProjectID($this->data()->project->getId());
        foreach ($this->data()->tags AS $newTagName) {
            if (!in_array($newTagName, $projectTags)) {
                $addTag = new AddTagToProject();
                $addTag->data()->projectID = $this->data()->project->getId();
                $addTag->data()->tag = $newTagName;
                $addTag->exec();
            }
        }
        foreach ($projectTags AS $oldTagName) {
            if (!in_array($oldTagName, $this->data()->tags)) {
                $remTag = new RemoveTagFromProject();
                $remTag->data()->projectID = $this->data()->project->getId();
                $remTag->data()->tag = $oldTagName;
                $remTag->exec();
            }
        }
    }

    private function setImpactTagsA()
    {
        $projectTags = (new ProjectImpactTagARepository())->getTagsNameByProjectID($this->data()->project->getId());
        foreach ($this->data()->impactTagsA AS $newTagName) {
            if (!in_array($newTagName, $projectTags)) {
                $addTag = new AddImpactTagAToProject();
                $addTag->data()->projectID = $this->data()->project->getId();
                $addTag->data()->tag = $newTagName;
                $addTag->exec();
            }
        }
        foreach ($projectTags AS $oldTagName) {
            if (!in_array($oldTagName, $this->data()->impactTagsA)) {
                $remTag = new RemoveImpactTagAFromProject();
                $remTag->data()->projectID = $this->data()->project->getId();
                $remTag->data()->tag = $oldTagName;
                $remTag->exec();
            }
        }
    }

    private function setImpactTagsB()
    {
        $projectTags = (new ProjectDsiFocusTagRepository())->getTagsNameByProjectID($this->data()->project->getId());
        foreach ($this->data()->impactTagsB AS $newTagName) {
            if (!in_array($newTagName, $projectTags)) {
                $addTag = new AddDsiFocusTagToProject();
                $addTag->data()->projectID = $this->data()->project->getId();
                $addTag->data()->tag = $newTagName;
                $addTag->exec();
            }
        }
        foreach ($projectTags AS $oldTagName) {
            if (!in_array($oldTagName, $this->data()->impactTagsB)) {
                $remTag = new RemoveDsiFocusTagFromProject();
                $remTag->data()->projectID = $this->data()->project->getId();
                $remTag->data()->tag = $oldTagName;
                $remTag->exec();
            }
        }
    }

    private function setImpactTagsC()
    {
        $projectTags = (new ProjectImpactTagCRepository())->getTagsNameByProjectID($this->data()->project->getId());
        foreach ($this->data()->impactTagsC AS $newTagName) {
            if (!in_array($newTagName, $projectTags)) {
                $addTag = new AddImpactTagCToProject();
                $addTag->data()->projectID = $this->data()->project->getId();
                $addTag->data()->tag = $newTagName;
                $addTag->exec();
            }
        }
        foreach ($projectTags AS $oldTagName) {
            if (!in_array($oldTagName, $this->data()->impactTagsC)) {
                $remTag = new RemoveImpactTagCFromProject();
                $remTag->data()->projectID = $this->data()->project->getId();
                $remTag->data()->tag = $oldTagName;
                $remTag->exec();
            }
        }
    }

    private function setLinks()
    {
        $this->data()->links = (array)$this->data()->links;
        $projectLinks = (new ProjectLinkRepository())->getLinksByProjectID($this->data()->project->getId());
        foreach ($this->data()->links AS $newLink) {
            if (!in_array($newLink, $projectLinks)) {
                $addLink = new AddLinkToProject();
                $addLink->data()->projectID = $this->data()->project->getId();
                $addLink->data()->link = $newLink;
                $addLink->exec();
            }
        }
        foreach ($projectLinks AS $oldLink) {
            if (!in_array($oldLink, $this->data()->links)) {
                $remLink = new RemoveLinkFromProject();
                $remLink->data()->projectID = $this->data()->project->getId();
                $remLink->data()->link = $oldLink;
                $remLink->exec();
            }
        }
    }

    private function setOrganisations()
    {
        $this->data()->organisations = (array)$this->data()->organisations;
        $organisationIDsForProject = (new OrganisationProjectRepository())->getOrganisationIDsForProject($this->data()->project->getId());
        foreach ($this->data()->organisations AS $newOrganisationID) {
            if (!in_array($newOrganisationID, $organisationIDsForProject)) {
                $addToOrg = new AddProjectToOrganisation();
                $addToOrg->data()->projectID = $this->data()->project->getId();
                $addToOrg->data()->organisationID = $newOrganisationID;
                $addToOrg->exec();
            }
        }
        foreach ($organisationIDsForProject AS $oldOrganisationID) {
            if (!in_array($oldOrganisationID, $this->data()->organisations)) {
                $remFromOrg = new RemoveProjectFromOrganisation();
                $remFromOrg->data()->projectID = $this->data()->project->getId();
                $remFromOrg->data()->organisationID = $oldOrganisationID;
                $remFromOrg->exec();
            }
        }
    }

    private function checkIfAllInfoHaveBeenSent()
    {
        //if (!isset($this->data()->name))
        //  throw new NotEnoughData('name');
        if (!isset($this->data()->executor))
            throw new NotEnoughData('user');
        if (!isset($this->data()->project))
            throw new NotEnoughData('project');
    }

    private function checkIfUserCanEditTheProject()
    {
        if ($this->data()->executor->getId() == $this->data()->project->getOwnerID())
            return true;
        if ($this->data()->executor->isCommunityAdmin())
            return true;
        $member = (new ProjectMemberRepository())->getByProjectIDAndMemberID(
            $this->data()->project->getId(),
            $this->data()->executor->getId()
        );
        if ($member AND $member->isAdmin())
            return true;

        $this->errorHandler->addTaggedError('user', 'Only the owner can make changes to the project');
        throw $this->errorHandler;
    }

    private function checkIfNameIsNotEmpty()
    {
        if (isset($this->data()->name))
            if ($this->data()->name == '') {
                $this->errorHandler->addTaggedError('name', 'Please type a project name');
                throw $this->errorHandler;
            }
    }

    private function setRegion()
    {
        $countryRegionRepo = new CountryRegionRepository();
        if ($this->data()->countryID != 0) {
            if ($countryRegionRepo->nameExists($this->data()->countryID, $this->data()->region)) {
                $countryRegion = $countryRegionRepo->getByName($this->data()->countryID, $this->data()->region);
            } else {
                $createCountryRegionCmd = new CreateCountryRegion();
                $createCountryRegionCmd->data()->countryID = $this->data()->countryID;
                $createCountryRegionCmd->data()->name = $this->data()->region;
                $createCountryRegionCmd->exec();
                $countryRegion = $createCountryRegionCmd->getCountryRegion();
            }

            $this->data()->project->setCountryRegion($countryRegion);
        }
    }

    private function saveImages()
    {
        if (isset($this->data()->logo)) {
            if ($this->data()->logo != Image::PROJECT_LOGO_URL . $this->data()->project->getLogo()) {
                $this->data()->project->setLogo(
                    $this->saveImage($this->data()->logo, Image::PROJECT_LOGO)
                );
            }
        }
        if (isset($this->data()->headerImage)) {
            if ($this->data()->headerImage != Image::PROJECT_HEADER_URL . $this->data()->project->getHeaderImage()) {
                $this->data()->project->setHeaderImage(
                    $this->saveImage($this->data()->headerImage, Image::PROJECT_HEADER)
                );
            }
        }

        $this->projectRepo->save($this->data()->project);
    }

    /**
     * @param $imageName
     * @param $imagePath
     * @return null|string
     */
    private function saveImage($imageName, $imagePath)
    {
        if ($imageName) {
            $orgFileName = basename($imageName);
            $orgFilePath = Image::TEMP_FOLDER . $orgFileName;
            $newFileName = $this->data()->project->getId() . '-' . $orgFileName;
            $newFilePath = $imagePath . $newFileName;

            $this->assertFileExistsOnServer($orgFilePath);

            $fileInfo = new \SplFileInfo($orgFilePath);
            $this->checkFileExtension($fileInfo);

            $img = new SimpleImage($orgFilePath);
            $img
                // ->thumbnail(200, 200)
                ->save($newFilePath, null, $fileInfo->getExtension());

            copy($orgFilePath, $newFilePath);

            return $newFileName;
        }

        return null;
    }

    private function assertFileExistsOnServer($orgFilePath)
    {
        if (!file_exists($orgFilePath))
            throw new NotFound('filePath');
    }

    private function checkFileExtension(\SplFileInfo $fileInfo)
    {
        if (!in_array(strtolower($fileInfo->getExtension()), [
            'jpg', 'jpeg', 'png'
        ])
        ) {
            $this->errorHandler->addTaggedError('file', 'Only image files accepted (received .' . $fileInfo->getExtension() . ')');
            $this->errorHandler->throwIfNotEmpty();
        }
    }
}

class UpdateProject_Data
{
    /** @var string */
    public $name,
        $shortDescription,
        $description,
        $socialImpact,
        $url,
        $status,
        $startDate,
        $endDate,
        $region,
        $logo,
        $headerImage;

    /** @var Project */
    public $project;

    /** @var string[] */
    public $tags,
        $impactTagsA,
        $impactTagsB,
        $impactTagsC,
        $links,
        $organisations;

    /** @var User */
    public $executor;

    /** @var int */
    public $countryID;
}