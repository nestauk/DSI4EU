<?php

namespace DSI\UseCase;

use abeautifulsite\SimpleImage;
use DSI\Entity\Image;
use DSI\Entity\Organisation;
use DSI\Entity\User;
use DSI\NotEnoughData;
use DSI\NotFound;
use DSI\Repository\CountryRegionRepo;
use DSI\Repository\OrganisationLinkRepo;
use DSI\Repository\OrganisationNetworkTagRepo;
use DSI\Repository\OrganisationProjectRepo;
use DSI\Repository\OrganisationRepo;
use DSI\Repository\OrganisationRepoInAPC;
use DSI\Repository\OrganisationSizeRepo;
use DSI\Repository\OrganisationTagRepo;
use DSI\Repository\OrganisationTypeRepo;
use DSI\Service\ErrorHandler;

class UpdateOrganisation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UpdateOrganisation_Data */
    private $data;

    /** @var OrganisationRepo */
    private $organisationRepo;

    public function __construct()
    {
        $this->data = new UpdateOrganisation_Data();
        $this->organisationRepo = new OrganisationRepoInAPC();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();

        $this->checkIfAllInfoHaveBeenSent();
        $this->checkIfUserCanEditTheOrganisation();
        $this->checkIfNameIsNotEmpty();
        $this->saveOrganisationDetails();
    }

    /**
     * @return UpdateOrganisation_Data
     */
    public function data()
    {
        return $this->data;
    }

    private function saveOrganisationDetails()
    {
        if (isset($this->data()->name))
            $this->data()->organisation->setName($this->data()->name);
        if (isset($this->data()->url))
            $this->data()->organisation->setUrl($this->data()->url);
        if (isset($this->data()->shortDescription))
            $this->data()->organisation->setShortDescription($this->data()->shortDescription);
        if (isset($this->data()->description))
            $this->data()->organisation->setDescription($this->data()->description);
        if (isset($this->data()->address))
            $this->data()->organisation->setAddress($this->data()->address);
        if (isset($this->data()->startDate))
            $this->data()->organisation->setStartDate($this->data()->startDate);
        if (isset($this->data()->organisationTypeId))
            if ($this->data()->organisationTypeId)
                $this->data()->organisation->setType(
                    (new OrganisationTypeRepo())->getById(
                        $this->data()->organisationTypeId
                    )
                );
        if (isset($this->data()->organisationSizeId))
            if ($this->data()->organisationSizeId)
                $this->data()->organisation->setSize(
                    (new OrganisationSizeRepo())->getById(
                        $this->data()->organisationSizeId
                    )
                );

        if (isset($this->data()->countryID) AND isset($this->data()->region))
            $this->setRegion();

        $this->organisationRepo->save($this->data()->organisation);

        if (isset($this->data()->tags))
            $this->setTags();
        if (isset($this->data()->networkTags))
            $this->setNetworkTags();
        if (isset($this->data()->projects))
            $this->setProjects();
        if (isset($this->data()->links))
            $this->setLinks();

        $this->saveImages();
    }

    private function checkIfAllInfoHaveBeenSent()
    {
        if (!isset($this->data()->executor))
            throw new NotEnoughData('executor');
        if (!isset($this->data()->organisation))
            throw new NotEnoughData('organisation');
    }

    private function checkIfUserCanEditTheOrganisation()
    {
        if (!($this->userCanEditOrganisation())) {
            $this->errorHandler->addTaggedError('user', 'You are not allowed to make changes to this organisation');
            throw $this->errorHandler;
        }
    }

    private function checkIfNameIsNotEmpty()
    {
        if (isset($this->data()->name))
            if ($this->data()->name == '')
                $this->errorHandler->addTaggedError('name', __('Please type a organisation name'));

        $this->errorHandler->throwIfNotEmpty();
    }

    private function setTags()
    {
        $orgTags = (new OrganisationTagRepo())->getTagNamesByOrganisation($this->data()->organisation);
        foreach ($this->data()->tags AS $newTagName) {
            if (!in_array($newTagName, $orgTags)) {
                $addTag = new AddTagToOrganisation();
                $addTag->data()->organisationID = $this->data()->organisation->getId();
                $addTag->data()->tag = $newTagName;
                $addTag->exec();
            }
        }
        foreach ($orgTags AS $oldTagName) {
            if (!in_array($oldTagName, $this->data()->tags)) {
                $remTag = new RemoveTagFromOrganisation();
                $remTag->data()->organisationID = $this->data()->organisation->getId();
                $remTag->data()->tag = $oldTagName;
                $remTag->exec();
            }
        }
    }

    private function setNetworkTags()
    {
        $orgNetworkTags = (new OrganisationNetworkTagRepo())->getTagNamesByOrganisation($this->data()->organisation);
        foreach ($this->data()->networkTags AS $newNetworkTagName) {
            if (!in_array($newNetworkTagName, $orgNetworkTags)) {
                $addTag = new AddNetworkTagToOrganisation();
                $addTag->setOrganisation($this->data()->organisation);
                $addTag->setTag($newNetworkTagName);
                $addTag->exec();
            }
        }
        foreach ($orgNetworkTags AS $oldTagName) {
            if (!in_array($oldTagName, $this->data()->networkTags)) {
                $remTag = new RemoveNetworkTagFromOrganisation();
                $remTag->setOrganisation($this->data()->organisation);
                $remTag->setTag($oldTagName);
                $remTag->exec();
            }
        }
    }

    private function setProjects()
    {
        $orgProjects = (new OrganisationProjectRepo())->getProjectIDsForOrganisation($this->data()->organisation);
        foreach ($this->data()->projects AS $newProject) {
            if (!in_array($newProject, $orgProjects)) {
                $addProject = new AddProjectToOrganisation();
                $addProject->data()->projectID = $newProject;
                $addProject->data()->organisationID = $this->data()->organisation->getId();
                $addProject->exec();
            }
        }
        foreach ($orgProjects AS $oldProject) {
            if (!in_array($oldProject, $this->data()->projects)) {
                $remProject = new RemoveProjectFromOrganisation();
                $remProject->data()->organisationID = $this->data()->organisation->getId();
                $remProject->data()->projectID = $oldProject;
                $remProject->exec();
            }
        }
    }

    private function setRegion()
    {
        $countryRegionRepo = new CountryRegionRepo();
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

            $this->data()->organisation->setCountryRegion($countryRegion);
        }
    }

    private function saveImages()
    {
        if (isset($this->data()->logo)) {
            if ($this->data()->logo != Image::ORGANISATION_LOGO_URL . $this->data()->organisation->getLogo()) {
                $this->data()->organisation->setLogo(
                    $this->saveImage($this->data()->logo, Image::ORGANISATION_LOGO)
                );
            }
        }
        if (isset($this->data()->headerImage)) {
            if ($this->data()->headerImage != Image::ORGANISATION_HEADER_URL . $this->data()->organisation->getHeaderImage()) {
                $this->data()->organisation->setHeaderImage(
                    $this->saveImage($this->data()->headerImage, Image::ORGANISATION_HEADER)
                );
            }
        }

        $this->organisationRepo->save($this->data()->organisation);
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
            $newFileName = $this->data()->organisation->getId() . '-' . $orgFileName;
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
            $this->errorHandler->addTaggedError('file', __('Only image files are accepted') . ' (received .' . $fileInfo->getExtension() . ')');
            $this->errorHandler->throwIfNotEmpty();
        }
    }

    private function setLinks()
    {
        $this->data()->links = (array)$this->data()->links;
        $organisationLinks = (new OrganisationLinkRepo())->getLinksByOrganisationID($this->data()->organisation->getId());
        foreach ($this->data()->links AS $newLink) {
            if (!in_array($newLink, $organisationLinks)) {
                $addLink = new AddLinkToOrganisation();
                $addLink->data()->organisationID = $this->data()->organisation->getId();
                $addLink->data()->link = $newLink;
                $addLink->exec();
            }
        }
        foreach ($organisationLinks AS $oldLink) {
            if (!in_array($oldLink, $this->data()->links)) {
                $remLink = new RemoveLinkFromOrganisation();
                $remLink->data()->organisationID = $this->data()->organisation->getId();
                $remLink->data()->link = $oldLink;
                $remLink->exec();
            }
        }
    }

    /**
     * @return bool
     */
    private function userCanEditOrganisation()
    {
        if ($this->data()->executor->isCommunityAdmin())
            return true;

        if ($this->data()->executor->getId() == $this->data()->organisation->getOwnerID())
            return true;

        return false;
    }
}

class UpdateOrganisation_Data
{
    /** @var User */
    public $executor;

    /** @var int */
    public $organisationTypeId,
        $organisationSizeId,
        $countryID;

    /** @var string */
    public $name,
        $url,
        $shortDescription,
        $description,
        $address,
        $startDate,
        $region,
        $logo,
        $headerImage;

    /** @var Organisation */
    public $organisation;

    /** @var string[] */
    public $tags,
        $networkTags,
        $links;

    /** @var int[] */
    public $projects;
}