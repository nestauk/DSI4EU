<?php

use DSI\Entity\FundingSource;

require_once __DIR__ . '/../../../config.php';

class FundingSourceTest extends \PHPUnit_Framework_TestCase
{
    /** @var FundingSource */
    private $fundingSource;

    public function setUp()
    {
        $this->fundingSource = new FundingSource();
    }

    /** @test setId, getId */
    public function settingAnId_returnsTheId()
    {
        $this->fundingSource->setId(1);
        $this->assertEquals(1, $this->fundingSource->getId());
    }

    /** @test setId */
    public function settingAnInvalidId_throwsException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->fundingSource->setId(0);
    }

    /** @test */
    public function settingTitle_returnsTitle()
    {
        $title = 'Brand New Funding Source';
        $this->fundingSource->setTitle($title);
        $this->assertEquals($title, $this->fundingSource->getTitle());
    }

    /** @test */
    public function settingUrl_returnsUrl()
    {
        $url = 'http://example.org';
        $this->fundingSource->setUrl($url);
        $this->assertEquals($url, $this->fundingSource->getUrl());
    }
}