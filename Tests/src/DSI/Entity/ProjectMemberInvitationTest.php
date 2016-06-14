<?php

require_once __DIR__ . '/../../../config.php';

class ProjectMemberInvitationTest extends \PHPUnit_Framework_TestCase
{
    /** @var \DSI\Entity\ProjectMemberInvitation */
    private $projectMemberInvitation;

    public function setUp()
    {
        $this->projectMemberInvitation = new \DSI\Entity\ProjectMemberInvitation();
    }

    /** @test setId, getId */
    public function settingDetails_returnsTheDetails()
    {
        $project = new \DSI\Entity\Project();
        $project->setId(1);
        $member = new \DSI\Entity\User();
        $member->setId(1);

        $this->projectMemberInvitation = new \DSI\Entity\ProjectMemberInvitation();
        $this->projectMemberInvitation->setProject($project);
        $this->projectMemberInvitation->setMember($member);

        $this->assertEquals($project->getId(), $this->projectMemberInvitation->getProjectID());
        $this->assertEquals($project->getId(), $this->projectMemberInvitation->getProject()->getId());
        $this->assertEquals($member->getId(), $this->projectMemberInvitation->getMemberID());
        $this->assertEquals($member->getId(), $this->projectMemberInvitation->getMember()->getId());
    }
}