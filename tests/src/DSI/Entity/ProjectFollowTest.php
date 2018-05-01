<?php

require_once __DIR__ . '/../../../config.php';

class ProjectFollowTest extends \PHPUnit_Framework_TestCase
{
    /** @var \DSI\Entity\ProjectFollow */
    private $projectFollow;

    public function setUp()
    {
        $this->projectFollow = new \DSI\Entity\ProjectFollow();
    }

    /** @test setId, getId */
    public function settingDetails_returnsTheDetails()
    {
        $project = new \DSI\Entity\Project();
        $project->setId(1);
        $user = new \DSI\Entity\User();
        $user->setId(1);

        $this->projectFollow = new \DSI\Entity\ProjectFollow();
        $this->projectFollow->setProject($project);
        $this->projectFollow->setUser($user);

        $this->assertEquals($project->getId(), $this->projectFollow->getProjectID());
        $this->assertEquals($project->getId(), $this->projectFollow->getProject()->getId());
        $this->assertEquals($user->getId(), $this->projectFollow->getUserID());
        $this->assertEquals($user->getId(), $this->projectFollow->getUser()->getId());
    }
}