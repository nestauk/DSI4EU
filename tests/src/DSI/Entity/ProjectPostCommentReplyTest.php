<?php

require_once __DIR__ . '/../../../config.php';

class ProjectPostCommentReplyTest extends \PHPUnit_Framework_TestCase
{
    /** @var \DSI\Entity\ProjectPostComment */
    private $projectPostComment;

    /** @var \DSI\Entity\User */
    private $user;

    public function setUp()
    {
        $this->projectPostComment = new \DSI\Entity\ProjectPostComment();
        $this->projectPostComment->setId(2);
        $this->user = new \DSI\Entity\User();
        $this->user->setId(3);
    }

    /** @test */
    public function settingDetails_returnsTheDetails()
    {
        $reply = new \DSI\Entity\ProjectPostCommentReply();
        $reply->setId($id = 1);
        $reply->setProjectPostComment($this->projectPostComment);
        $reply->setUser($this->user);
        $reply->setComment($comment = 'new comment');
        $reply->setTime($time = '2016-05-16 14:36:36');

        $this->assertEquals($id, $reply->getId());
        $this->assertEquals($this->projectPostComment->getId(), $reply->getProjectPostComment()->getId());
        $this->assertEquals($this->user->getId(), $reply->getUser()->getId());
        $this->assertEquals($comment, $reply->getComment());
        $this->assertEquals($time, $reply->getTime());
    }
}