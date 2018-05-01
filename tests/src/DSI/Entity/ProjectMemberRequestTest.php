<?php

require_once __DIR__ . '/../../../config.php';

class ProjectMemberRequestTest extends \PHPUnit_Framework_TestCase
{
    /** @var \DSI\Entity\ProjectMemberRequest */
    private $projectMemberRequest;

    public function setUp()
    {
        $this->projectMemberRequest = new \DSI\Entity\ProjectMemberRequest();
    }

    /** @test setId, getId */
    public function settingDetails_returnsTheDetails()
    {
        $project = new \DSI\Entity\Project();
        $project->setId(1);
        $member = new \DSI\Entity\User();
        $member->setId(1);

        $this->projectMemberRequest = new \DSI\Entity\ProjectMemberRequest();
        $this->projectMemberRequest->setProject($project);
        $this->projectMemberRequest->setMember($member);

        $this->assertEquals($project->getId(), $this->projectMemberRequest->getProjectID());
        $this->assertEquals($project->getId(), $this->projectMemberRequest->getProject()->getId());
        $this->assertEquals($member->getId(), $this->projectMemberRequest->getMemberID());
        $this->assertEquals($member->getId(), $this->projectMemberRequest->getMember()->getId());
    }
}