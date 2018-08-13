<?php

namespace DSI\UseCase\CaseStudy;

use abeautifulsite\SimpleImage;
use DSI\Entity\CaseStudy;
use DSI\Entity\CountryRegion;
use DSI\Entity\Image;
use DSI\NotFound;
use DSI\Repository\CaseStudyRepo;
use DSI\Repository\CountryRegionRepo;
use DSI\Repository\OrganisationRepoInAPC;
use DSI\Repository\ProjectRepoInAPC;
use DSI\Service\ErrorHandler;
use DSI\UseCase\CreateCountryRegion;
use Models\Relationship\CaseStudyTag;

class CaseStudyCreate
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var CaseStudyRepo */
    private $caseStudyRepo;

    /** @var CaseStudy */
    private $caseStudy;

    /** @var CaseStudyCreate_Data */
    private $data;

    /** @var CountryRegionRepo */
    private $countryRegionRepo;

    /** @var int[] */
    public $tagIDs;

    public function __construct()
    {
        $this->data = new CaseStudyCreate_Data();
        $this->errorHandler = new ErrorHandler();
        $this->caseStudyRepo = new CaseStudyRepo();
        $this->countryRegionRepo = new CountryRegionRepo();
    }

    public function exec()
    {
        $this->assertTitleHasBeenSent();
        $this->unsetSamePositionOnFirstPage();
        $this->createCaseStudy();
        $this->saveImages();
        $this->saveTags();
    }

    /**
     * @return CaseStudyCreate_Data
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return CaseStudy
     */
    public function getCaseStudy()
    {
        return $this->caseStudy;
    }

    private function assertTitleHasBeenSent()
    {
        if ($this->data()->title == '') {
            $this->errorHandler->addTaggedError('title', 'Please type case study name');
            throw $this->errorHandler;
        }
    }

    private function unsetSamePositionOnFirstPage()
    {
        if ($this->data()->positionOnHomePage > 0) {
            try {
                $caseStudy = $this->caseStudyRepo->getByPositionOnHomePage((int)$this->data()->positionOnHomePage);
                $caseStudy->setPositionOnFirstPage(0);
                $this->caseStudyRepo->save($caseStudy);
            } catch (NotFound $e) {
            }
        }
    }

    private function createCaseStudy()
    {
        $caseStudy = new CaseStudy();
        $caseStudy->setTitle((string)$this->data()->title);
        $caseStudy->setIntroCardText((string)$this->data()->introCardText);
        $caseStudy->setIntroPageText((string)$this->data()->introPageText);
        $caseStudy->setInfoText((string)$this->data()->infoText);
        $caseStudy->setMainText((string)$this->data()->mainText);
        $caseStudy->setProjectStartDate((string)$this->data()->projectStartDate);
        $caseStudy->setProjectEndDate((string)$this->data()->projectEndDate);
        $caseStudy->setUrl((string)$this->data()->url);
        $caseStudy->setButtonLabel((string)$this->data()->buttonLabel);
        $caseStudy->setCardColour((string)$this->data()->cardColour);
        $caseStudy->setIsPublished((bool)$this->data()->isPublished);
        $caseStudy->setIsFeaturedOnSlider((bool)$this->data()->isFeaturedOnSlider);
        $caseStudy->setPositionOnFirstPage((int)$this->data()->positionOnHomePage);
        if($this->data()->projectID)
            $caseStudy->setProject( (new ProjectRepoInAPC())->getById($this->data()->projectID) );
        if($this->data()->organisationID)
            $caseStudy->setOrganisation( (new OrganisationRepoInAPC())->getById($this->data()->organisationID) );

        $this->caseStudyRepo->insert($caseStudy);
        $this->caseStudy = $caseStudy;
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
            $newFileName = $this->caseStudy->getId() . '-' . $orgFileName;
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

    private function saveImages()
    {
        $this->caseStudy->setCardImage(
            $this->saveImage($this->data()->cardBgImage, Image::CASE_STUDY_CARD_BG)
        );
        $this->caseStudyRepo->save($this->caseStudy);
    }

    private function saveTags()
    {
        CaseStudyTag::where(CaseStudyTag::CaseStudyID, $this->caseStudy->getId())
            ->delete();

        foreach ((array)$this->tagIDs AS $tagID => $value) {
            if ($value) {
                $caseStudyTag = new CaseStudyTag();
                $caseStudyTag->{CaseStudyTag::CaseStudyID} = $this->caseStudy->getId();
                $caseStudyTag->{CaseStudyTag::TagID} = $tagID;
                $caseStudyTag->save();
            }
        }
    }
}

class CaseStudyCreate_Data
{
    /** @var string */
    public $title,
        $introCardText,
        $introPageText,
        $infoText,
        $mainText,
        $projectStartDate,
        $projectEndDate,
        $url,
        $buttonLabel,
        $cardColour;

    /** @var bool */
    public $isPublished,
        $isFeaturedOnSlider,
        $positionOnHomePage;

    /** @var string */
    public $logoImage,
        $cardBgImage,
        $headerImage;

    /** @var int */
    public $countryID;

    /** @var int */
    public $projectID,
        $organisationID;
}