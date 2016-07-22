<?php

use DSI\Entity\ProjectLink;

require_once __DIR__ . '/../../../config.php';

class ProjectLinkTest extends \PHPUnit_Framework_TestCase
{
    /** @var ProjectLink */
    private $projectLink;

    public function setUp()
    {
        $this->projectLink = new ProjectLink();
    }

    /** @test setId, getId */
    public function settingDetails_returnsTheDetails()
    {
        $project = new \DSI\Entity\Project();
        $project->setId(1);

        $url = 'http://example.com';

        $this->projectLink= new ProjectLink();
        $this->projectLink->setProject($project);
        $this->projectLink->setLink($url);

        $this->assertEquals($project->getId(), $this->projectLink->getProjectID());
        $this->assertEquals($project->getId(), $this->projectLink->getProject()->getId());
        $this->assertEquals($url, $this->projectLink->getLink());
    }
}