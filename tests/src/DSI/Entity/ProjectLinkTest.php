<?php

use \DSI\Entity\ProjectLink;
use \DSI\Entity\ProjectLink_Service;

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

    /** @test */
    public function gettingTheCorrectService()
    {
        $this->projectLink = new ProjectLink();

        $this->checkLinkService('http://facebook.com/', ProjectLink_Service::Facebook);
        $this->checkLinkService('http://twitter.com/', ProjectLink_Service::Twitter);
        $this->checkLinkService('http://plus.google.com/', ProjectLink_Service::GooglePlus);
        $this->checkLinkService('http://github.com/', ProjectLink_Service::GitHub);
        $this->checkLinkService('http://inoveb.com/', ProjectLink_Service::Other);
    }

    private function checkLinkService($link, $service)
    {
        $this->projectLink->setLink($link);
        $this->assertEquals(
            $service, $this->projectLink->getLinkService()
        );
    }
}