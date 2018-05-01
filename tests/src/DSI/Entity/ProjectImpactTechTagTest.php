<?php

require_once __DIR__ . '/../../../config.php';

class ProjectImpactTechTagTest extends \PHPUnit_Framework_TestCase
{
    /** @var \DSI\Entity\ProjectImpactTechTag */
    private $projectTag;

    public function setUp()
    {
        $this->projectTag = new \DSI\Entity\ProjectImpactTechTag();
    }

    /** @test setId, getId */
    public function settingDetails_returnsTheDetails()
    {
        $project = new \DSI\Entity\Project();
        $project->setId(1);
        $tag = new \DSI\Entity\ImpactTag();
        $tag->setId(1);

        $this->projectTag = new \DSI\Entity\ProjectImpactTechTag();
        $this->projectTag->setProject($project);
        $this->projectTag->setTag($tag);

        $this->assertEquals($project->getId(), $this->projectTag->getProjectID());
        $this->assertEquals($project->getId(), $this->projectTag->getProject()->getId());
        $this->assertEquals($tag->getId(), $this->projectTag->getTagID());
        $this->assertEquals($tag->getId(), $this->projectTag->getTag()->getId());
    }
}