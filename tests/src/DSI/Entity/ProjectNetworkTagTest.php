<?php

require_once __DIR__ . '/../../../config.php';

class ProjectNetworkTagTest extends \PHPUnit_Framework_TestCase
{
    /** @var \DSI\Entity\ProjectNetworkTag */
    private $projectNetworkTag;

    public function setUp()
    {
        $this->projectNetworkTag = new \DSI\Entity\ProjectNetworkTag();
    }

    /** @test setId, getId */
    public function settingDetails_returnsTheDetails()
    {
        $project = new \DSI\Entity\Project();
        $project->setId(1);
        $tag = new \DSI\Entity\NetworkTag();
        $tag->setId(1);

        $this->projectNetworkTag = new \DSI\Entity\ProjectNetworkTag();
        $this->projectNetworkTag->setProject($project);
        $this->projectNetworkTag->setTag($tag);

        $this->assertEquals($project->getId(), $this->projectNetworkTag->getProjectID());
        $this->assertEquals($project->getId(), $this->projectNetworkTag->getProject()->getId());
        $this->assertEquals($tag->getId(), $this->projectNetworkTag->getTagID());
        $this->assertEquals($tag->getId(), $this->projectNetworkTag->getTag()->getId());
    }
}