<?php

namespace DSI\UseCase;

use abeautifulsite\SimpleImage;
use DSI\Entity\CaseStudy;
use DSI\Entity\CountryRegion;
use DSI\Entity\Image;
use DSI\NotFound;
use DSI\Repository\CaseStudyRepository;
use DSI\Repository\CountryRegionRepository;
use DSI\Service\ErrorHandler;

class EditCaseStudy
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var CaseStudyRepository */
    private $caseStudyRepo;

    /** @var CaseStudy */
    private $caseStudy;

    /** @var EditCaseStudy_Data */
    private $data;

    /** @var CountryRegionRepository */
    private $countryRegionRepo;

    /** @var CountryRegion */
    private $countryRegion;

    public function __construct()
    {
        $this->data = new EditCaseStudy_Data();
        $this->errorHandler = new ErrorHandler();
        $this->caseStudyRepo = new CaseStudyRepository();
        $this->countryRegionRepo = new CountryRegionRepository();
    }

    public function exec()
    {
        $this->caseStudy = $this->caseStudyRepo->getById($this->data()->caseStudyId);
        $this->assertTitleHasBeenSent();
        $this->getRegion();
        $this->editCaseStudy();
        $this->saveImages();
    }

    /**
     * @return EditCaseStudy_Data
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

    private function editCaseStudy()
    {
        $this->caseStudy->setTitle((string)$this->data()->title);
        $this->caseStudy->setIntroCardText((string)$this->data()->introCardText);
        $this->caseStudy->setIntroPageText((string)$this->data()->introPageText);
        $this->caseStudy->setMainText((string)$this->data()->mainText);
        $this->caseStudy->setProjectStartDate((string)$this->data()->projectStartDate);
        $this->caseStudy->setProjectEndDate((string)$this->data()->projectEndDate);
        $this->caseStudy->setUrl((string)$this->data()->url);
        $this->caseStudy->setButtonLabel((string)$this->data()->buttonLabel);
        $this->caseStudy->setCardColour((string)$this->data()->cardColour);
        $this->caseStudy->setIsPublished((bool)$this->data()->isPublished);
        $this->caseStudy->setIsFeaturedOnSlider((bool)$this->data()->isFeaturedOnSlider);
        $this->caseStudy->setIsFeaturedOnHomePage((bool)$this->data()->isFeaturedOnHomePage);
        if ($this->countryRegion)
            $this->caseStudy->setRegion($this->countryRegion);

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
        if ($this->data()->logoImage != Image::CASE_STUDY_LOGO_URL . $this->caseStudy->getLogo()) {
            $this->caseStudy->setLogo(
                $this->saveImage($this->data()->logoImage, Image::CASE_STUDY_LOGO)
            );
        }
        if ($this->data()->cardBgImage != Image::CASE_STUDY_CARD_BG_URL . $this->caseStudy->getCardImage()) {
            $this->caseStudy->setCardImage(
                $this->saveImage($this->data()->cardBgImage, Image::CASE_STUDY_CARD_BG)
            );
        }
        if ($this->data()->headerImage != Image::CASE_STUDY_HEADER_URL . $this->caseStudy->getHeaderImage()) {
            $this->caseStudy->setHeaderImage(
                $this->saveImage($this->data()->headerImage, Image::CASE_STUDY_HEADER)
            );
        }

        $this->caseStudyRepo->save($this->caseStudy);
    }

    private function getRegion()
    {
        if ($this->data()->countryID != 0) {
            if ($this->countryRegionRepo->nameExists($this->data()->countryID, $this->data()->region)) {
                $countryRegion = $this->countryRegionRepo->getByName($this->data()->countryID, $this->data()->region);
            } else {
                $createCountryRegionCmd = new CreateCountryRegion();
                $createCountryRegionCmd->data()->countryID = $this->data()->countryID;
                $createCountryRegionCmd->data()->name = $this->data()->region;
                $createCountryRegionCmd->exec();
                $countryRegion = $createCountryRegionCmd->getCountryRegion();
            }
            $this->countryRegion = $countryRegion;
        }
    }
}

class EditCaseStudy_Data
{
    /** @var int */
    public $caseStudyId;

    /** @var string */
    public $title,
        $introCardText,
        $introPageText,
        $mainText,
        $projectStartDate,
        $projectEndDate,
        $url,
        $buttonLabel,
        $cardColour;

    /** @var bool */
    public $isPublished,
        $isFeaturedOnSlider,
        $isFeaturedOnHomePage;

    /** @var string */
    public $logoImage,
        $cardBgImage,
        $headerImage;

    /** @var int */
    public $countryID;

    /** @var string */
    public $region;
}