<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository\CaseStudyRepository;
use \DSI\Repository\CountryRegionRepository;
use \DSI\Repository\CountryRepository;
use \DSI\Entity\CaseStudy;
use \DSI\Entity\CountryRegion;
use \DSI\Entity\Country;

class CaseStudyRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var CaseStudyRepository */
    private $caseStudyRepository;

    /** @var CountryRegionRepository */
    private $countryRegionRepo;

    /** @var CountryRepository */
    private $countryRepo;

    /** @var Country */
    private $country;

    /** @var CountryRegion */
    private $countryRegion;

    public function setUp()
    {
        $this->caseStudyRepository = new CaseStudyRepository();
        $this->countryRegionRepo = new CountryRegionRepository();
        $this->countryRepo = new CountryRepository();

        $this->country = new Country();
        $this->country->setName('testCountry');
        $this->countryRepo->insert($this->country);

        $this->countryRegion = new CountryRegion();
        $this->countryRegion->setName('testRegion');
        $this->countryRegion->setCountry($this->country);
        $this->countryRegionRepo->insert($this->countryRegion);
    }

    public function tearDown()
    {
        $this->caseStudyRepository->clearAll();
        $this->countryRegionRepo->clearAll();
        $this->countryRepo->clearAll();
    }

    /** @test saveAsNew */
    public function caseStudyCanBeCreated()
    {
        $caseStudy = new CaseStudy();
        $caseStudy->setTitle($title = 'Title');
        $caseStudy->setIntroCardText($introCardText = 'Intro Card Text');
        $caseStudy->setIntroPageText($introPageText = 'Intro Page Text');
        $caseStudy->setMainText($mainText = 'Main Text');
        $caseStudy->setProjectStartDate($projectStartDate = '2016-10-10');
        $caseStudy->setProjectEndDate($projectEndDate = '2016-10-11');
        $caseStudy->setUrl($url = 'http://example.org');
        $caseStudy->setButtonLabel($buttonLabel = 'Button Label');
        $caseStudy->setLogo($logo = 'DSC100-logo.JPG');
        $caseStudy->setCardImage($cardImage = 'DSC100-cardImage.JPG');
        $caseStudy->setHeaderImage($headerImage = 'DSC100-headerImage.JPG');
        $caseStudy->setCardColour($cardColour = '#ffffff');
        $caseStudy->setIsPublished($isPublished = true);
        $caseStudy->setRegion($this->countryRegion);
        $this->caseStudyRepository->insert($caseStudy);

        $this->assertEquals(1, $caseStudy->getId());
        $caseStudy = $this->caseStudyRepository->getById($caseStudy->getId());
        $this->assertEquals($title, $caseStudy->getTitle());
        $this->assertEquals($introCardText, $caseStudy->getIntroCardText());
        $this->assertEquals($introPageText, $caseStudy->getIntroPageText());
        $this->assertEquals($mainText, $caseStudy->getMainText());
        $this->assertEquals($projectStartDate, $caseStudy->getProjectStartDate());
        $this->assertEquals($projectEndDate, $caseStudy->getProjectEndDate());
        $this->assertEquals($url, $caseStudy->getUrl());
        $this->assertEquals($buttonLabel, $caseStudy->getButtonLabel());
        $this->assertEquals($logo, $caseStudy->getLogo());
        $this->assertEquals($cardImage, $caseStudy->getCardImage());
        $this->assertEquals($headerImage, $caseStudy->getHeaderImage());
        $this->assertEquals($cardColour, $caseStudy->getCardColour());
        $this->assertEquals($isPublished, $caseStudy->isPublished());
        $this->assertEquals($this->countryRegion->getId(), $caseStudy->getRegion()->getId());
        $this->assertEquals($this->countryRegion->getId(), $caseStudy->getRegionID());
    }

    /** @test getByID */
    public function gettingAnNonExistentProjectById_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->caseStudyRepository->getById(1);
    }

    /** @test save */
    public function NonexistentProjectCannotBeSaved()
    {
        $caseStudy = new CaseStudy();
        $caseStudy->setId(1);

        $this->setExpectedException(\DSI\NotFound::class);
        $this->caseStudyRepository->save($caseStudy);
    }

    /** @test getAll */
    public function getAllProjects()
    {
        $caseStudy = new CaseStudy();
        $this->caseStudyRepository->insert($caseStudy);

        $this->assertCount(1, $this->caseStudyRepository->getAll());

        $caseStudy = new CaseStudy();
        $this->caseStudyRepository->insert($caseStudy);

        $this->assertCount(2, $this->caseStudyRepository->getAll());
    }

    /** @test */
    public function projectCanBeUpdated()
    {
        $caseStudy = new CaseStudy();
        $this->caseStudyRepository->insert($caseStudy);

        $caseStudy->setTitle($title = 'Title');
        $caseStudy->setIntroCardText($introCardText = 'Intro Card Text');
        $caseStudy->setIntroPageText($introPageText = 'Intro Page Text');
        $caseStudy->setMainText($mainText = 'Main Text');
        $caseStudy->setProjectStartDate($projectStartDate = '2016-10-10');
        $caseStudy->setProjectEndDate($projectEndDate = '2016-10-11');
        $caseStudy->setUrl($url = 'http://example.org');
        $caseStudy->setButtonLabel($buttonLabel = 'Button Label');
        $caseStudy->setLogo($logo = 'DSC100-logo.JPG');
        $caseStudy->setCardImage($cardImage = 'DSC100-cardImage.JPG');
        $caseStudy->setHeaderImage($headerImage = 'DSC100-headerImage.JPG');
        $caseStudy->setCardColour($cardColour = '#ffffff');
        $caseStudy->setIsPublished($isPublished = true);
        $caseStudy->setRegion($this->countryRegion);
        $this->caseStudyRepository->save($caseStudy);

        $caseStudy = $this->caseStudyRepository->getById($caseStudy->getId());
        $this->assertEquals($title, $caseStudy->getTitle());
        $this->assertEquals($introCardText, $caseStudy->getIntroCardText());
        $this->assertEquals($introPageText, $caseStudy->getIntroPageText());
        $this->assertEquals($mainText, $caseStudy->getMainText());
        $this->assertEquals($projectStartDate, $caseStudy->getProjectStartDate());
        $this->assertEquals($projectEndDate, $caseStudy->getProjectEndDate());
        $this->assertEquals($url, $caseStudy->getUrl());
        $this->assertEquals($buttonLabel, $caseStudy->getButtonLabel());
        $this->assertEquals($logo, $caseStudy->getLogo());
        $this->assertEquals($cardImage, $caseStudy->getCardImage());
        $this->assertEquals($headerImage, $caseStudy->getHeaderImage());
        $this->assertEquals($cardColour, $caseStudy->getCardColour());
        $this->assertEquals($isPublished, $caseStudy->isPublished());
        $this->assertEquals($this->countryRegion->getId(), $caseStudy->getRegion()->getId());
        $this->assertEquals($this->countryRegion->getId(), $caseStudy->getRegionID());
    }

    /** @test */
    public function setNullStartDateAndEndDate()
    {
        $caseStudy = new CaseStudy();
        $caseStudy->setProjectStartDate(NULL);
        $caseStudy->setProjectEndDate(NULL);
        $this->caseStudyRepository->insert($caseStudy);

        $sameProject = $this->caseStudyRepository->getById($caseStudy->getId());
        $this->assertEmpty($sameProject->getProjectStartDate());
        $this->assertEmpty($sameProject->getProjectEndDate());
    }
}