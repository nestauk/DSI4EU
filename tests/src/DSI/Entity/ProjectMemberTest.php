<?php

require_once __DIR__ . '/../../../config.php';

class ProjectMemberTest extends \PHPUnit_Framework_TestCase
{
    /** @var \DSI\Entity\ProjectMember */
    private $projectMember;

    public function setUp()
    {
        $this->projectMember = new \DSI\Entity\ProjectMember();
    }

    /** @test setId, getId */
    public function settingDetails_returnsTheDetails()
    {
        $project = new \DSI\Entity\Project();
        $project->setId(1);
        $member = new \DSI\Entity\User();
        $member->setId(1);

        $this->projectMember = new \DSI\Entity\ProjectMember();
        $this->projectMember->setProject($project);
        $this->projectMember->setMember($member);
        $this->projectMember->setIsAdmin(true);

        $this->assertEquals($project->getId(), $this->projectMember->getProjectID());
        $this->assertEquals($project->getId(), $this->projectMember->getProject()->getId());
        $this->assertEquals($member->getId(), $this->projectMember->getMemberID());
        $this->assertEquals($member->getId(), $this->projectMember->getMember()->getId());
        $this->assertTrue($this->projectMember->isAdmin());
    }
}