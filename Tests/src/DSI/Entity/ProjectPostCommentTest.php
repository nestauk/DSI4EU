<?php

require_once __DIR__ . '/../../../config.php';

class ProjectPostCommentTest extends \PHPUnit_Framework_TestCase
{
    /** @var \DSI\Entity\ProjectPost */
    private $projectPost;

    /** @var \DSI\Entity\User */
    private $user;

    public function setUp()
    {
        $this->projectPost = new \DSI\Entity\ProjectPost();
        $this->projectPost->setId(2);
        $this->user = new \DSI\Entity\User();
        $this->user->setId(3);
    }

    /** @test setId, getId */
    public function settingDetails_returnsTheDetails()
    {
        $postComment = new \DSI\Entity\ProjectPostComment();
        $postComment->setId($id = 1);
        $postComment->setProjectPost($this->projectPost);
        $postComment->setUser($this->user);
        $postComment->setComment($comment = 'new comment');
        $postComment->setTime($time = '2016-05-16 14:36:36');
        $postComment->setRepliesCount($replies = 10);

        $this->assertEquals($id, $postComment->getId());
        $this->assertEquals($this->projectPost->getId(), $postComment->getProjectPost()->getId());
        $this->assertEquals($this->user->getId(), $postComment->getUser()->getId());
        $this->assertEquals($comment, $postComment->getComment());
        $this->assertEquals($time, $postComment->getTime());
        $this->assertEquals($replies, $postComment->getRepliesCount());
    }
}