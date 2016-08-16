<?php

use \DSI\Entity\CaseStudy;
use \DSI\Repository\CountryRepository;
use \DSI\Repository\CountryRegionRepository;

require_once __DIR__ . '/../../../config.php';

class CaseStudyTest extends \PHPUnit_Framework_TestCase
{
    /** @var CaseStudy */
    private $caseStudy;

    /** @var CountryRepository */
    private $countryRepo;

    /** @var  CountryRegionRepository */
    private $regionRepo;

    public function setUp()
    {
        $this->caseStudy = new CaseStudy();

        $this->countryRepo = new CountryRepository();
        $this->regionRepo = new CountryRegionRepository();
    }

    public function tearDown()
    {
        $this->countryRepo->clearAll();
        $this->regionRepo->clearAll();
    }

    /** @test */
    public function settingAnId_returnsTheId()
    {
        $this->caseStudy->setId(1);
        $this->assertEquals(1, $this->caseStudy->getId());
    }

    /** @test setId */
    public function settingAnInvalidId_throwsException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->caseStudy->setId(0);
    }

    /** @test */
    public function settingTitle_returnsTitle()
    {
        $this->assertEquals('', $this->caseStudy->getTitle());

        $title = 'Case Study Title';
        $this->caseStudy->setTitle($title);
        $this->assertEquals($title, $this->caseStudy->getTitle());
    }

    /** @test */
    public function settingIntroCardText_returnsIntroCardText()
    {
        $this->assertEquals('', $this->caseStudy->getIntroCardText());

        $introCardText = 'Intro Text';
        $this->caseStudy->setIntroCardText($introCardText);
        $this->assertEquals($introCardText, $this->caseStudy->getIntroCardText());
    }

    /** @test */
    public function settingIntroPageText_returnsIntroPageText()
    {
        $this->assertEquals('', $this->caseStudy->getIntroPageText());

        $introPageText = 'Intro Text';
        $this->caseStudy->setIntroPageText($introPageText);
        $this->assertEquals($introPageText, $this->caseStudy->getIntroPageText());
    }

    /** @test */
    public function settingMainText_returnsMainText()
    {
        $this->assertEquals('', $this->caseStudy->getMainText());

        $mainText = 'Main Text';
        $this->caseStudy->setMainText($mainText);
        $this->assertEquals($mainText, $this->caseStudy->getMainText());
    }

    /** @test */
    public function settingProjectStartDate_returnsProjectStartDate()
    {
        $this->assertEquals('', $this->caseStudy->getProjectStartDate());

        $date = '2016-12-10';
        $this->caseStudy->setProjectStartDate($date);
        $this->assertEquals($date, $this->caseStudy->getProjectStartDate());
        $this->assertEquals('10-12-2016', $this->caseStudy->getProjectStartDate('d-m-Y'));
    }

    /** @test */
    public function settingProjectEndDate_returnsProjectEndDate()
    {
        $this->assertEquals('', $this->caseStudy->getProjectEndDate());

        $date = '2016-10-10';
        $this->caseStudy->setProjectEndDate($date);
        $this->assertEquals($date, $this->caseStudy->getProjectEndDate());
    }

    /** @test */
    public function settingUrl_returnsUrl()
    {
        $this->assertEquals('', $this->caseStudy->getUrl());

        $url = 'http://example.org';
        $this->caseStudy->setUrl($url);
        $this->assertEquals($url, $this->caseStudy->getUrl());
    }

    /** @test */
    public function settingButtonLabel_returnsButtonLabel()
    {
        $this->assertEquals('', $this->caseStudy->getButtonLabel());

        $label = 'View Project';
        $this->caseStudy->setButtonLabel($label);
        $this->assertEquals($label, $this->caseStudy->getButtonLabel());
    }

    /** @test */
    public function settingLogo_returnsLogo()
    {
        $this->assertEquals('', $this->caseStudy->getLogo());

        $logo = 'DSC100.JPG';
        $this->caseStudy->setLogo($logo);
        $this->assertEquals($logo, $this->caseStudy->getLogo());
    }

    /** @test */
    public function settingLogo_returnsLogoOrDefault()
    {
        $this->assertEquals(CaseStudy::DEFAULT_LOGO, $this->caseStudy->getLogoOrDefault());

        $logo = 'DSC100.JPG';
        $this->caseStudy->setLogo($logo);
        $this->assertEquals($logo, $this->caseStudy->getLogoOrDefault());
    }

    /** @test */
    public function settingCardImage_returnsCardImage()
    {
        $this->assertEquals('', $this->caseStudy->getCardImage());

        $image = 'DSC100.JPG';
        $this->caseStudy->setCardImage($image);
        $this->assertEquals($image, $this->caseStudy->getCardImage());
    }

    /** @test */
    public function settingHeaderImage_returnsHeaderImage()
    {
        $this->assertEquals('', $this->caseStudy->getHeaderImage());

        $bgImage = 'DSC100.JPG';
        $this->caseStudy->setHeaderImage($bgImage);
        $this->assertEquals($bgImage, $this->caseStudy->getHeaderImage());
    }

    /** @test */
    public function settingHeaderImage_returnsHeaderImageOrDefault()
    {
        $this->assertEquals(CaseStudy::DEFAULT_HEADER, $this->caseStudy->getHeaderImageOrDefault());

        $bgImage = 'DSC100.JPG';
        $this->caseStudy->setHeaderImage($bgImage);
        $this->assertEquals($bgImage, $this->caseStudy->getHeaderImageOrDefault());
    }

    /** @test */
    public function settingCardColour_returnsCardColour()
    {
        $this->assertEquals('', $this->caseStudy->getCardColour());

        $colour = '#ffffff';
        $this->caseStudy->setCardColour($colour);
        $this->assertEquals($colour, $this->caseStudy->getCardColour());
    }

    /** @test */
    public function settingIsPublished_returnsIsPublished()
    {
        $this->assertEquals(false, $this->caseStudy->isPublished());

        $this->caseStudy->setIsPublished(true);
        $this->assertEquals(true, $this->caseStudy->isPublished());

        $this->caseStudy->setIsPublished(false);
        $this->assertEquals(false, $this->caseStudy->isPublished());
    }

    /** @test */
    public function settingIsFeaturedOnSlider_returnsIsFeaturedOnSlider()
    {
        $this->assertEquals(false, $this->caseStudy->isFeaturedOnSlider());

        $this->caseStudy->setIsFeaturedOnSlider(true);
        $this->assertEquals(true, $this->caseStudy->isFeaturedOnSlider());

        $this->caseStudy->setIsFeaturedOnSlider(false);
        $this->assertEquals(false, $this->caseStudy->isFeaturedOnSlider());
    }

    /** @test */
    public function settingIsFeaturedOnHomePage_returnsIsFeaturedOnHomePage()
    {
        $this->assertEquals(false, $this->caseStudy->isFeaturedOnHomePage());

        $this->caseStudy->setIsFeaturedOnHomePage(true);
        $this->assertEquals(true, $this->caseStudy->isFeaturedOnHomePage());

        $this->caseStudy->setIsFeaturedOnHomePage(false);
        $this->assertEquals(false, $this->caseStudy->isFeaturedOnHomePage());
    }

    /** @test */
    public function settingRegion_returnsRegion()
    {
        $country = new \DSI\Entity\Country();
        $country->setName($countryName = 'Romania');
        $this->countryRepo->insert($country);

        $region = new \DSI\Entity\CountryRegion();
        $region->setName($regionName = 'Iasi');
        $region->setCountry($country);
        $this->regionRepo->insert($region);

        $this->caseStudy->setRegion($region);

        $this->assertEquals($country->getId(), $this->caseStudy->getCountryId());
        $this->assertEquals($country->getName(), $this->caseStudy->getCountryName());
        $this->assertEquals($region->getName(), $this->caseStudy->getRegionName());

        $this->caseStudy->unsetRegion();
        $this->assertEquals(0, $this->caseStudy->getCountryId());
        $this->assertEquals(0, $this->caseStudy->getRegionID());
    }
}