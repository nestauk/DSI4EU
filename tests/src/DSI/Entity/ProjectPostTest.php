<?php

require_once __DIR__ . '/../../../config.php';

class ProjectPostTest extends \PHPUnit_Framework_TestCase
{
    /** @var \DSI\Entity\ProjectPost */
    private $projectPost;

    /** @var \DSI\Entity\User */
    private $user;

    public function setUp()
    {
        $this->projectPost = new \DSI\Entity\ProjectPost();
        $this->user = new \DSI\Entity\User();
    }

    /** @test setId, getId */
    public function settingDetails_returnsTheDetails()
    {
        $post = new \DSI\Entity\ProjectPost();
        $post->setId($id = 1);

        $project = new \DSI\Entity\Project();
        $project->setId(11);
        $post->setProject($project);

        $user = new \DSI\Entity\User();
        $user->setId(111);
        $post->setUser($user);

        $post->setTitle($title = 'Post Title');
        $post->setText($text = 'Post Text');
        $post->setTime($time = '2016-05-16 14:36:36');
        $post->setCommentsCount($commentsCount = '52');

        $this->assertEquals($id, $post->getId());
        $this->assertEquals($project->getId(), $post->getProject()->getId());
        $this->assertEquals($user->getId(), $post->getUser()->getId());
        $this->assertEquals($title, $post->getTitle());
        $this->assertEquals($text, $post->getText());
        $this->assertEquals($commentsCount, $post->getCommentsCount());
    }
}