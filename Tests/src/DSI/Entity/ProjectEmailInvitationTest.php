<?php

require_once __DIR__ . '/../../../config.php';

class ProjectEmailInvitationTest extends \PHPUnit_Framework_TestCase
{
    /** @var \DSI\Entity\ProjectEmailInvitation */
    private $projectEmailInvitation;

    public function setUp()
    {
        $this->projectEmailInvitation = new \DSI\Entity\ProjectEmailInvitation();
    }

    /** @test setId, getId */
    public function settingDetails_returnsTheDetails()
    {
        $project = new \DSI\Entity\Project();
        $project->setId(1);
        $byUser = new \DSI\Entity\User();
        $byUser->setId(1);

        $this->projectEmailInvitation = new \DSI\Entity\ProjectEmailInvitation();
        $this->projectEmailInvitation->setProject($project);
        $this->projectEmailInvitation->setByUser($byUser);
        $this->projectEmailInvitation->setEmail($email = 'test@example.org');
        $this->projectEmailInvitation->setDate($date = '2016-10-12');

        $this->assertEquals($project->getId(), $this->projectEmailInvitation->getProjectID());
        $this->assertEquals($project->getId(), $this->projectEmailInvitation->getProject()->getId());
        $this->assertEquals($byUser->getId(), $this->projectEmailInvitation->getByUserID());
        $this->assertEquals($byUser->getId(), $this->projectEmailInvitation->getByUser()->getId());
        $this->assertEquals($email, $this->projectEmailInvitation->getEmail());
        $this->assertEquals($date, $this->projectEmailInvitation->getDate());
    }
}