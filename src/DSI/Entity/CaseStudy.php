<?php

namespace DSI\Entity;

class CaseStudy
{
    const DEFAULT_HEADER = '0.png';
    const DEFAULT_LOGO = '0.png';

    /** @var integer */
    private $id,
        $positionOnFirstPage;

    /** @var string */
    private $title,
        $introCardText,
        $introPageText,
        $infoText,
        $mainText,
        $projectStartDate,
        $projectEndDate,
        $url,
        $buttonLabel,
        $logo,
        $cardImage,
        $headerImage,
        $cardColour;

    /** @var bool */
    private $isPublished,
        $isFeaturedOnSlider; // not used anymore

    /** @var Project */
    private $project;

    /** @var Organisation */
    private $organisation;

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int)$this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        if ($id <= 0)
            throw new \InvalidArgumentException('id: ' . $id);

        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return (string)$this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = (string)$title;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return (string)$this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = (string)$url;
    }

    /**
     * @return string
     */
    public function getIntroCardText()
    {
        return (string)$this->introCardText;
    }

    /**
     * @param string $introCardText
     */
    public function setIntroCardText($introCardText)
    {
        $this->introCardText = (string)$introCardText;
    }

    /**
     * @return string
     */
    public function getInfoText(): string
    {
        return (string)$this->infoText;
    }

    /**
     * @param string $infoText
     */
    public function setInfoText(string $infoText)
    {
        $this->infoText = (string)$infoText;
    }

    /**
     * @return string
     */
    public function getMainText()
    {
        return (string)$this->mainText;
    }

    /**
     * @param string $mainText
     */
    public function setMainText($mainText)
    {
        $this->mainText = (string)$mainText;
    }

    /**
     * @return string
     */
    public function getHeaderImage()
    {
        return (string)$this->headerImage;
    }

    /**
     * @return string
     */
    public function getHeaderImageOrDefault()
    {
        return (string)($this->headerImage ? $this->headerImage : self::DEFAULT_HEADER);
    }

    /**
     * @param string $headerImage
     */
    public function setHeaderImage($headerImage)
    {
        $this->headerImage = (string)$headerImage;
    }

    /**
     * @return string
     */
    public function getLogo()
    {
        return (string)$this->logo;
    }

    /**
     * @return string
     */
    public function getLogoOrDefault()
    {
        return (string)($this->logo ? $this->logo : self::DEFAULT_LOGO);
    }

    /**
     * @param string $logo
     */
    public function setLogo($logo)
    {
        $this->logo = (string)$logo;
    }

    /**
     * @return string
     */
    public function getButtonLabel()
    {
        return (string)$this->buttonLabel;
    }

    /**
     * @param string $buttonLabel
     */
    public function setButtonLabel($buttonLabel)
    {
        $this->buttonLabel = (string)$buttonLabel;
    }

    /**
     * @return string
     */
    public function getIntroPageText(): string
    {
        return (string)$this->introPageText;
    }

    /**
     * @param string $introPageText
     */
    public function setIntroPageText(string $introPageText)
    {
        $this->introPageText = $introPageText;
    }

    /**
     * @param null $format
     * @return string
     */
    public function getProjectStartDate($format = null): string
    {
        if ($format !== null) {
            return date($format, strtotime($this->projectStartDate));
        }

        return (string)$this->projectStartDate;
    }

    /**
     * @param string $projectStartDate
     */
    public function setProjectStartDate($projectStartDate)
    {
        if ($projectStartDate == '0000-00-00')
            $projectStartDate = '';
        $this->projectStartDate = (string)$projectStartDate;
    }

    /**
     * @return string
     */
    public function getProjectEndDate(): string
    {
        return (string)$this->projectEndDate;
    }

    /**
     * @param string $projectEndDate
     */
    public function setProjectEndDate($projectEndDate)
    {
        if ($projectEndDate == '0000-00-00')
            $projectEndDate = '';

        $this->projectEndDate = (string)$projectEndDate;
    }

    /**
     * @return string
     */
    public function getCardImage(): string
    {
        return (string)$this->cardImage;
    }

    /**
     * @param string $cardImage
     */
    public function setCardImage($cardImage)
    {
        $this->cardImage = (string)$cardImage;
    }

    /**
     * @return string
     */
    public function getCardColour(): string
    {
        return (string)$this->cardColour;
    }

    /**
     * @param string $cardColour
     */
    public function setCardColour($cardColour)
    {
        $this->cardColour = (string)$cardColour;
    }

    /**
     * @return boolean
     */
    public function isPublished(): bool
    {
        return (bool)$this->isPublished;
    }

    /**
     * @param boolean $isPublished
     */
    public function setIsPublished(bool $isPublished)
    {
        $this->isPublished = (bool)$isPublished;
    }

    /**
     * @return boolean
     */
    public function isFeaturedOnSlider(): bool
    {
        return (bool)$this->isFeaturedOnSlider;
    }

    /**
     * @param boolean $isFeaturedOnSlider
     */
    public function setIsFeaturedOnSlider($isFeaturedOnSlider)
    {
        $this->isFeaturedOnSlider = (bool)$isFeaturedOnSlider;
    }

    /**
     * @return int
     */
    public function getPositionOnFirstPage(): int
    {
        return (int)$this->positionOnFirstPage;
    }

    /**
     * @param int $positionOnFirstPage
     */
    public function setPositionOnFirstPage($positionOnFirstPage)
    {
        $this->positionOnFirstPage = (int)$positionOnFirstPage;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @return int
     */
    public function getProjectID()
    {
        return $this->project ? $this->project->getId() : 0;
    }

    /**
     * @param Project $project
     */
    public function setProject(Project $project)
    {
        $this->project = $project;
    }

    public function unsetProject()
    {
        $this->project = null;
    }

    /**
     * @return Organisation
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }

    /**
     * @return int
     */
    public function getOrganisationID()
    {
        return $this->organisation ? $this->organisation->getId() : 0;
    }

    /**
     * @param Organisation $organisation
     */
    public function setOrganisation(Organisation $organisation)
    {
        $this->organisation = $organisation;
    }

    public function unsetOrganisation()
    {
        $this->organisation = null;
    }
}