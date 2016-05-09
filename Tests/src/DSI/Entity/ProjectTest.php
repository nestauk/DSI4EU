<?php

use DSI\Entity\Project;

require_once __DIR__ . '/../../../config.php';

class ProjectTest extends \PHPUnit_Framework_TestCase
{
    /** @var Project */
    private $project;

    /** @var \DSI\Entity\User */
    private $owner;

    public function setUp()
    {
        $this->project = new Project();
        $this->owner = new \DSI\Entity\User();
        $this->owner->setId(1);
    }

    /** @test setId, getId */
    public function settingAnId_returnsTheId()
    {
        $this->project->setId(1);
        $this->assertEquals(1, $this->project->getId());
    }

    /** @test setId */
    public function settingAnInvalidId_throwsException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->project->setId(0);
    }

    /** @test setName, getName */
    public function settingName_returnsName()
    {
        $projectName = 'Brand New Project';
        $this->project->setName($projectName);
        $this->assertEquals($projectName, $this->project->getName());
    }

    /** @test setDescription, getDescription */
    public function settingDescription_returnsDescription()
    {
        $projectDesc = 'Brand New Project Desc';
        $this->project->setDescription($projectDesc);
        $this->assertEquals($projectDesc, $this->project->getDescription());
    }

    /** @test setUrl, getUrl */
    public function settingUrl_returnsUrl()
    {
        $projectUrl = 'http://example.org';
        $this->project->setUrl($projectUrl);
        $this->assertEquals($projectUrl, $this->project->getUrl());
    }

    /** @test setStatus, getStatus */
    public function settingStatus_returnsStatus()
    {
        $status = 'closed';
        $this->project->setStatus($status);
        $this->assertEquals($status, $this->project->getStatus());
    }

    /** @test setStartDate, getStartDate */
    public function settingStartDate_returnsStartDate()
    {
        $startDate = '2016-05-21';
        $this->project->setStartDate($startDate);
        $this->assertEquals($startDate, $this->project->getStartDate());
    }

    /** @test setStartDate, getStartDate */
    public function settingNullStartDate_returnsStartDate()
    {
        $startDate = null;
        $this->project->setStartDate($startDate);
        $this->assertEquals($startDate, $this->project->getStartDate());
    }

    /** @test setEndDate, getEndDate */
    public function settingEndDate_returnsEndDate()
    {
        $EndDate = '2016-05-21';
        $this->project->setEndDate($EndDate);
        $this->assertEquals($EndDate, $this->project->getEndDate());
    }

    /** @test setEndDate, getEndDate */
    public function settingNullEndDate_returnsEndDate()
    {
        $EndDate = null;
        $this->project->setEndDate($EndDate);
        $this->assertEquals($EndDate, $this->project->getEndDate());
    }

    /** @test setOwner, getOwner */
    public function settingOwner_returnsOwner()
    {
        $this->project->setOwner($this->owner);
        $this->assertEquals($this->owner->getId(), $this->project->getOwner()->getId());
    }
}