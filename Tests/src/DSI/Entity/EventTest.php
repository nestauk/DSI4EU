<?php

use DSI\Entity\Event;

require_once __DIR__ . '/../../../config.php';

class EventTest extends \PHPUnit_Framework_TestCase
{
    /** @var Event */
    private $event;

    /** @var  \DSI\Entity\CountryRegion */
    private $region;

    public function setUp()
    {
        $this->event = new Event();

        $this->region = new \DSI\Entity\CountryRegion();
        $this->region->setId(rand(11, 20));
        $this->region->setName('Country Name');
    }

    /** @test setId, getId */
    public function settingAnId_returnsTheId()
    {
        $this->assertEquals(0, $this->event->getId());

        $this->event->setId(1);
        $this->assertEquals(1, $this->event->getId());
    }

    /** @test setId */
    public function settingAnInvalidId_throwsException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->event->setId(0);
    }

    /** @test */
    public function settingTitle_returnsTitle()
    {
        $this->assertEquals('', $this->event->getTitle());

        $title = 'Brand New Event';
        $this->event->setTitle($title);
        $this->assertEquals($title, $this->event->getTitle());
    }

    /** @test */
    public function settingUrl_returnsUrl()
    {
        $this->assertEquals('', $this->event->getUrl());

        $url = 'http://example.org';
        $this->event->setUrl($url);
        $this->assertEquals($url, $this->event->getUrl());
    }

    /** @test */
    public function settingShortDescription_returnsShortDescription()
    {
        $this->assertEquals('', $this->event->getShortDescription());

        $shortDesc = 'New Funding Opportunity';
        $this->event->setShortDescription($shortDesc);
        $this->assertEquals($shortDesc, $this->event->getShortDescription());
    }

    /** @test */
    public function settingDescription_returnsDescription()
    {
        $this->assertEquals('', $this->event->getDescription());

        $desc = 'New Funding Opportunity';
        $this->event->setDescription($desc);
        $this->assertEquals($desc, $this->event->getDescription());
    }

    /** @test */
    public function settingStartDate_canGetStartDate()
    {
        $this->assertEquals('', $this->event->getStartDate());

        $startDate = '2016-10-12';
        $this->event->setStartDate($startDate);
        $this->assertEquals($startDate, $this->event->getStartDate());
    }

    /** @test */
    public function settingEndDate_canGetEndDate()
    {
        $this->assertEquals('', $this->event->getEndDate());

        $endDate = '2016-10-12';
        $this->event->setEndDate($endDate);
        $this->assertEquals($endDate, $this->event->getEndDate());
    }

    /** @test */
    public function settingTimeCreated_returnsTimeCreated()
    {
        $this->assertEquals('', $this->event->getTimeCreated());

        $timeCreated = '2016-10-12 11:05:34';
        $this->event->setTimeCreated($timeCreated);
        $this->assertEquals($timeCreated, $this->event->getTimeCreated());
    }

    /** @test */
    public function settingEmptyStartDate_returnsEmptyStartDate()
    {
        $this->event->setStartDate('0000-00-00');
        $this->assertEquals('', $this->event->getStartDate());
    }

    /** @test */
    public function settingEmptyEndDate_returnsEmptyEndDate()
    {
        $this->event->setEndDate('0000-00-00');
        $this->assertEquals('', $this->event->getEndDate());
    }

    /** @test */
    public function settingEmptyTimeCreated_returnsEmptyTimeCreated()
    {
        $this->event->setTimeCreated('0000-00-00 00:00:00');
        $this->assertEquals('', $this->event->getTimeCreated());
    }

    /** @test */
    public function settingCountryRegion_canGetCountryRegion()
    {
        $this->event->setRegion($this->region);
        $this->assertEquals($this->region->getId(), $this->event->getRegionID());
        $this->assertEquals($this->region->getName(), $this->event->getRegionName());
    }

    /** @test */
    public function canCheckIfIsNew()
    {
        $this->event->setTimeCreated(date('Y-m-d', $this->daysAgo(6)));
        $this->assertTrue($this->event->isNew());

        $this->event->setTimeCreated(date('Y-m-d', $this->daysAgo(8)));
        $this->assertFalse($this->event->isNew());
    }

    /**
     * @param int $days
     * @return int
     */
    private function daysAgo($days)
    {
        $daysAgo = time() - 60 * 60 * 24 * $days;
        return $daysAgo;
    }
}