<?php

use DSI\Entity\Funding;
use DSI\Entity\FundingSource;

require_once __DIR__ . '/../../../config.php';

class FundingTest extends \PHPUnit_Framework_TestCase
{
    /** @var Funding */
    private $funding;

    /** @var FundingSource */
    private $fundingSource;

    /** @var \DSI\Entity\FundingType */
    private $fundingType;

    /** @var  \DSI\Entity\Country */
    private $country;

    public function setUp()
    {
        $this->funding = new Funding();

        $this->fundingSource = new FundingSource();
        $this->fundingSource->setId(rand(1, 10));
        $this->fundingSource->setTitle('Random Source Title');

        $this->fundingType = new \DSI\Entity\FundingType();
        $this->fundingType->setId(rand(1, 10));
        $this->fundingType->setTitle('Random Type Title');

        $this->country = new \DSI\Entity\Country();
        $this->country->setId(rand(11, 20));
        $this->country->setName('Country Name');
    }

    /** @test setId, getId */
    public function settingAnId_returnsTheId()
    {
        $this->assertEquals(0, $this->funding->getId());

        $this->funding->setId(1);
        $this->assertEquals(1, $this->funding->getId());
    }

    /** @test setId */
    public function settingAnInvalidId_throwsException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->funding->setId(0);
    }

    /** @test */
    public function settingTitle_returnsTitle()
    {
        $this->assertEquals('', $this->funding->getTitle());

        $title = 'Brand New Funding';
        $this->funding->setTitle($title);
        $this->assertEquals($title, $this->funding->getTitle());
    }

    /** @test */
    public function settingUrl_returnsUrl()
    {
        $this->assertEquals('', $this->funding->getUrl());

        $url = 'http://example.org';
        $this->funding->setUrl($url);
        $this->assertEquals($url, $this->funding->getUrl());
    }

    /** @test */
    public function settingDescription_returnsDescription()
    {
        $this->assertEquals('', $this->funding->getDescription());

        $desc = 'New Funding Opportunity';
        $this->funding->setDescription($desc);
        $this->assertEquals($desc, $this->funding->getDescription());
    }

    /** @test */
    public function settingClosingDate_returnsClosingDate()
    {
        $this->assertEquals('', $this->funding->getClosingDate());

        $closingDate = '2016-10-12';
        $this->funding->setClosingDate($closingDate);
        $this->assertEquals($closingDate, $this->funding->getClosingDate());
        $this->assertEquals('12th October 2016', $this->funding->getClosingDate('jS F Y'));
    }

    /** @test */
    public function settingEmptyClosingDate_returnsEmptyClosingDate()
    {
        $this->funding->setClosingDate('0000-00-00');
        $this->assertEquals('', $this->funding->getClosingDate());
        $this->assertEquals('', $this->funding->getClosingDate('jS F Y'));
    }

    /** @test */
    public function settingTimeCreated_returnsTimeCreated()
    {
        $this->assertEquals('', $this->funding->getTimeCreated());

        $timeCreated = '2016-10-12 11:05:34';
        $this->funding->setTimeCreated($timeCreated);
        $this->assertEquals($timeCreated, $this->funding->getTimeCreated());
    }

    /** @test */
    public function settingEmptyTimeCreated_returnsEmptyTimeCreated()
    {
        $this->funding->setTimeCreated('0000-00-00 00:00:00');
        $this->assertEquals('', $this->funding->getTimeCreated());
    }

    /** @test */
    public function settingFundingSource_returnsFundingSource()
    {
        $this->funding->setSource($this->fundingSource);
        $this->assertEquals($this->fundingSource->getId(), $this->funding->getSource()->getId());
        $this->assertEquals($this->fundingSource->getTitle(), $this->funding->getSourceTitle());
    }

    /** @test */
    public function settingFundingType_returnsFundingType()
    {
        $this->funding->setType($this->fundingType);
        $this->assertEquals($this->fundingType->getId(), $this->funding->getType()->getId());
        $this->assertEquals($this->fundingType->getId(), $this->funding->getTypeID());
    }

    /** @test */
    public function canAddTargets()
    {
        $this->assertEmpty($this->funding->getTargetIDs());

        $target1 = new \DSI\Entity\FundingTarget();
        $target1->setId(1);
        $target2 = new \DSI\Entity\FundingTarget();
        $target2->setId(2);
        $target3 = new \DSI\Entity\FundingTarget();
        $target3->setId(3);

        $this->funding->addTarget($target1);
        $this->assertCount(1, $this->funding->getTargetIDs());
        $this->assertContains($target1->getId(), $this->funding->getTargetIDs());

        $this->funding->addTarget($target2);
        $this->assertCount(2, $this->funding->getTargetIDs());
        $this->assertContains($target2->getId(), $this->funding->getTargetIDs());

        $this->funding->addTarget($target3);
        $this->assertCount(3, $this->funding->getTargetIDs());
        $this->assertContains($target3->getId(), $this->funding->getTargetIDs());
    }

    /** @test */
    public function canRemoveTargets()
    {
        $this->assertEmpty($this->funding->getTargetIDs());

        $target1 = new \DSI\Entity\FundingTarget();
        $target1->setId(1);
        $target2 = new \DSI\Entity\FundingTarget();
        $target2->setId(2);
        $target3 = new \DSI\Entity\FundingTarget();
        $target3->setId(3);

        $this->funding->addTarget($target1);
        $this->funding->addTarget($target2);
        $this->funding->addTarget($target3);

        $this->funding->removeAllTargets();
        $this->assertEmpty($this->funding->getTargetIDs());
    }

    /** @test */
    public function settingCountry_returnsCountry()
    {
        $this->funding->setCountry($this->country);
        $this->assertEquals($this->country->getId(), $this->funding->getCountryID());
        $this->assertEquals($this->country->getName(), $this->funding->getCountryName());
    }

    /** @test */
    public function canCheckIfIsNew()
    {
        $this->funding->setTimeCreated(date('Y-m-d', $this->daysAgo(6)));
        $this->assertTrue($this->funding->isNew());

        $this->funding->setTimeCreated(date('Y-m-d', $this->daysAgo(8)));
        $this->assertFalse($this->funding->isNew());
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