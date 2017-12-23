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

class CaseStudyEdit
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var CaseStudyRepo */
    private $caseStudyRepo;

    /** @var CaseStudy */
    private $caseStudy;

    /** @var CaseStudyEdit_Data */
    private $data;

    /** @var CountryRegionRepo */
    private $countryRegionRepo;

    /** @var CountryRegion */
    private $countryRegion;

    public function __construct()
    {
        $this->data = new CaseStudyEdit_Data();
        $this->errorHandler = new ErrorHandler();
        $this->caseStudyRepo = new CaseStudyRepo();
        $this->countryRegionRepo = new CountryRegionRepo();
    }

    public function exec()
    {
        $this->caseStudy = $this->caseStudyRepo->getById($this->data()->caseStudyId);
        $this->assertTitleHasBeenSent();
        $this->unsetSamePositionOnFirstPage();
        $this->editCaseStudy();
        $this->saveImages();
    }

    /**
     * @return CaseStudyEdit_Data
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

    private function editCaseStudy()
    {
        $this->caseStudy->setTitle((string)$this->data()->title);
        $this->caseStudy->setIntroCardText((string)$this->data()->introCardText);
        $this->caseStudy->setIntroPageText((string)$this->data()->introPageText);
        $this->caseStudy->setInfoText((string)$this->data()->infoText);
        $this->caseStudy->setMainText((string)$this->data()->mainText);
        $this->caseStudy->setProjectStartDate((string)$this->data()->projectStartDate);
        $this->caseStudy->setProjectEndDate((string)$this->data()->projectEndDate);
        $this->caseStudy->setUrl((string)$this->data()->url);
        $this->caseStudy->setButtonLabel((string)$this->data()->buttonLabel);
        $this->caseStudy->setCardColour((string)$this->data()->cardColour);
        $this->caseStudy->setIsPublished((bool)$this->data()->isPublished);
        $this->caseStudy->setIsFeaturedOnSlider((bool)$this->data()->isFeaturedOnSlider);
        $this->caseStudy->setPositionOnFirstPage((int)$this->data()->positionOnHomePage);
        if($this->data()->projectID)
            $this->caseStudy->setProject( (new ProjectRepoInAPC())->getById($this->data()->projectID) );
        else
            $this->caseStudy->unsetProject();
        if($this->data()->organisationID)
            $this->caseStudy->setOrganisation( (new OrganisationRepoInAPC())->getById($this->data()->organisationID) );
        else
            $this->caseStudy->unsetOrganisation();

        $this->caseStudyRepo->save($this->caseStudy);
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
        if ($this->data()->cardBgImage != Image::CASE_STUDY_CARD_BG_URL . $this->caseStudy->getCardImage()) {
            $this->caseStudy->setCardImage(
                $this->saveImage($this->data()->cardBgImage, Image::CASE_STUDY_CARD_BG)
            );
        }

        $this->caseStudyRepo->save($this->caseStudy);
    }
}

class CaseStudyEdit_Data
{
    /** @var int */
    public $caseStudyId;

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
    public $cardBgImage;

    /** @var int */
    public $projectID,
        $organisationID;
}