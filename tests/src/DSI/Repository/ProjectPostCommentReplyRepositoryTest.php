<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository;
use \DSI\Repository\UserRepo;
use \DSI\Repository\ProjectRepo;
use \DSI\Repository\ProjectPostRepo;
use \DSI\Repository\ProjectPostCommentRepo;
use \DSI\Entity\ProjectPostComment;
use \DSI\Entity\ProjectPost;
use \DSI\Entity\Project;
use \DSI\Entity\User;
use \DSI\Entity;

class ProjectPostCommentReplyRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var Repository\ProjectPostCommentReplyRepo */
    private $projectPostCommentReplyRepo;

    /** @var ProjectPostCommentRepo */
    private $projectPostCommentRepo;

    /** @var ProjectPostRepo */
    private $projectPostRepo;

    /** @var ProjectRepo */
    private $projectRepo;

    /** @var UserRepo */
    private $userRepo;

    /** @var Project */
    private $project;

    /** @var User */
    private $user;

    /** @var ProjectPost */
    private $post;

    /** @var ProjectPostComment */
    private $comment;

    public function setUp()
    {
        $this->projectPostCommentReplyRepo = new Repository\ProjectPostCommentReplyRepo();
        $this->projectPostCommentRepo = new ProjectPostCommentRepo();
        $this->projectPostRepo = new ProjectPostRepo();
        $this->projectRepo = new ProjectRepo();
        $this->userRepo = new UserRepo();

        $this->user = new User();
        $this->userRepo->insert($this->user);

        $this->project = new Project();
        $this->project->setOwner($this->user);
        $this->projectRepo->insert($this->project);

        $this->post = new ProjectPost();
        $this->post->setUser($this->user);
        $this->post->setProject($this->project);
        $this->projectPostRepo->insert($this->post);

        $this->comment = new ProjectPostComment();
        $this->comment->setProjectPost($this->post);
        $this->comment->setUser($this->user);
        $this->comment->setComment('comment');
        $this->projectPostCommentRepo->insert($this->comment);
    }

    public function tearDown()
    {
        $this->projectPostCommentReplyRepo->clearAll();
        $this->projectPostCommentRepo->clearAll();
        $this->projectPostRepo->clearAll();
        $this->projectRepo->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test insert */
    public function replyCanBeAdded()
    {
        $reply = new Entity\ProjectPostCommentReply();
        $reply->setUser($this->user);
        $reply->setProjectPostComment($this->comment);
        $reply->setComment($comment = 'new comment');
        $this->projectPostCommentReplyRepo->insert($reply);

        $this->assertEquals(1, $reply->getId());

        $reply = $this->projectPostCommentReplyRepo->getById($reply->getId());
        $this->assertEquals($this->user->getId(), $reply->getUserId());
        $this->assertEquals($this->post->getId(), $reply->getProjectPostCommentID());
        $this->assertEquals($comment, $reply->getComment());
        $this->assertRegExp('[^\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}:\d{2}$]', $reply->getTime());
    }

    /** @test insert */
    public function cannotAddWithoutAComment()
    {
        $reply = new Entity\ProjectPostCommentReply();
        $reply->setUser($this->user);
        $this->setExpectedException(InvalidArgumentException::class);
        $this->projectPostCommentReplyRepo->insert($reply);
    }

    /** @test insert */
    public function cannotAddWithoutAnUser()
    {
        $reply = new Entity\ProjectPostCommentReply();
        $reply->setProjectPostComment($this->comment);
        $this->setExpectedException(InvalidArgumentException::class);
        $this->projectPostCommentReplyRepo->insert($reply);
    }

    /** @test insert */
    public function cannotAddAnEmptyReply()
    {
        $reply = new Entity\ProjectPostCommentReply();
        $reply->setUser($this->user);
        $reply->setProjectPostComment($this->comment);
        $this->setExpectedException(InvalidArgumentException::class);
        $this->projectPostCommentReplyRepo->insert($reply);
    }

    /** @test */
    public function replyCanBeUpdated()
    {
        $reply = new Entity\ProjectPostCommentReply();
        $reply->setUser($this->user);
        $reply->setProjectPostComment($this->comment);
        $reply->setComment($comment = 'new comment');
        $this->projectPostCommentReplyRepo->insert($reply);

        $reply = $this->projectPostCommentReplyRepo->getById($reply->getId());
        $reply->setUser($this->user);
        $reply->setProjectPostComment($this->comment);
        $reply->setComment($comment = 'modified comment');
        $this->projectPostCommentReplyRepo->save($reply);

        $reply = $this->projectPostCommentReplyRepo->getById($reply->getId());
        $this->assertEquals($this->user->getId(), $reply->getUserId());
        $this->assertEquals($this->comment->getId(), $reply->getProjectPostCommentID());
        $this->assertEquals($comment, $reply->getComment());
        $this->assertRegExp('[^\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}:\d{2}$]', $reply->getTime());
    }

    /** @test getByID */
    public function gettingAnNonExistentCommentById_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->projectPostCommentReplyRepo->getById(1);
    }

    /** @test save */
    public function NonexistentReplyCannotBeSaved()
    {
        $reply = new Entity\ProjectPostCommentReply();
        $reply->setId(1);
        $reply->setProjectPostComment($this->comment);
        $reply->setUser($this->user);
        $reply->setComment('new comment');

        $this->setExpectedException(\DSI\NotFound::class);
        $this->projectPostCommentReplyRepo->save($reply);
    }

    /** @test getAll */
    public function getAllReplies()
    {
        $reply = new \DSI\Entity\ProjectPostCommentReply();
        $reply->setUser($this->user);
        $reply->setProjectPostComment($this->comment);
        $reply->setComment($comment = 'new comment');
        $this->projectPostCommentReplyRepo->insert($reply);

        $this->assertCount(1, $this->projectPostCommentReplyRepo->getAll());

        $reply = new \DSI\Entity\ProjectPostCommentReply();
        $reply->setUser($this->user);
        $reply->setProjectPostComment($this->comment);
        $reply->setComment($comment = 'new comment');
        $this->projectPostCommentReplyRepo->insert($reply);

        $this->assertCount(2, $this->projectPostCommentReplyRepo->getAll());
    }
}