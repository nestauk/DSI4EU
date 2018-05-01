<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository\UserRepo;
use \DSI\Repository\ProjectRepo;
use \DSI\Repository\ProjectPostRepo;
use \DSI\Repository\ProjectPostCommentRepo;
use \DSI\Entity\ProjectPostComment;
use \DSI\Entity\ProjectPost;
use \DSI\Entity\Project;
use \DSI\Entity\User;

class ProjectPostCommentRepositoryTest extends PHPUnit_Framework_TestCase
{
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
    private $post1, $post2;

    public function setUp()
    {
        $this->projectPostCommentRepo = new ProjectPostCommentRepo();
        $this->projectPostRepo = new ProjectPostRepo();
        $this->projectRepo = new ProjectRepo();
        $this->userRepo = new UserRepo();

        $this->user = new User();
        $this->userRepo->insert($this->user);

        $this->project = new Project();
        $this->project->setOwner($this->user);
        $this->projectRepo->insert($this->project);

        $this->post1 = new ProjectPost();
        $this->post1->setUser($this->user);
        $this->post1->setProject($this->project);
        $this->projectPostRepo->insert($this->post1);

        $this->post2 = new ProjectPost();
        $this->post2->setUser($this->user);
        $this->post2->setProject($this->project);
        $this->projectPostRepo->insert($this->post2);
    }

    public function tearDown()
    {
        $this->projectPostCommentRepo->clearAll();
        $this->projectPostRepo->clearAll();
        $this->projectRepo->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test insert */
    public function commentCanBeAdded()
    {
        $postComment = new ProjectPostComment();
        $postComment->setUser($this->user);
        $postComment->setProjectPost($this->post1);
        $postComment->setComment($comment = 'new comment');
        $this->projectPostCommentRepo->insert($postComment);

        $this->assertEquals(1, $postComment->getId());

        $postComment = $this->projectPostCommentRepo->getById($postComment->getId());
        $this->assertEquals($this->user->getId(), $postComment->getUserId());
        $this->assertEquals($this->post1->getId(), $postComment->getProjectPostId());
        $this->assertEquals($comment, $postComment->getComment());
        $this->assertRegExp('[^\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}:\d{2}$]', $postComment->getTime());
    }

    /** @test insert */
    public function cannotAddWithoutAProjectPost()
    {
        $postComment = new ProjectPostComment();
        $postComment->setUser($this->user);
        $this->setExpectedException(InvalidArgumentException::class);
        $this->projectPostCommentRepo->insert($postComment);
    }

    /** @test insert */
    public function cannotAddWithoutAnUser()
    {
        $postComment = new ProjectPostComment();
        $postComment->setProjectPost($this->post1);
        $this->setExpectedException(InvalidArgumentException::class);
        $this->projectPostCommentRepo->insert($postComment);
    }

    /** @test insert */
    public function cannotAddAnEmptyComment()
    {
        $postComment = new ProjectPostComment();
        $postComment->setUser($this->user);
        $postComment->setProjectPost($this->post1);
        $this->setExpectedException(InvalidArgumentException::class);
        $this->projectPostCommentRepo->insert($postComment);
    }

    /** @test save, getByID */
    public function commentCanBeUpdated()
    {
        $postComment = new ProjectPostComment();
        $postComment->setUser($this->user);
        $postComment->setProjectPost($this->post1);
        $postComment->setComment($comment = 'new comment');
        $this->projectPostCommentRepo->insert($postComment);

        $postComment = $this->projectPostCommentRepo->getById($postComment->getId());
        $postComment->setUser($this->user);
        $postComment->setProjectPost($this->post2);
        $postComment->setComment($comment = 'modified comment');
        $this->projectPostCommentRepo->save($postComment);

        $postComment = $this->projectPostCommentRepo->getById($postComment->getId());
        $this->assertEquals($this->user->getId(), $postComment->getUserId());
        $this->assertEquals($this->post2->getId(), $postComment->getProjectPostId());
        $this->assertEquals($comment, $postComment->getComment());
        $this->assertRegExp('[^\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}:\d{2}$]', $postComment->getTime());
    }

    /** @test getByID */
    public function gettingAnNonExistentCommentById_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->projectPostCommentRepo->getById(1);
    }

    /** @test save */
    public function NonexistentCommentCannotBeSaved()
    {
        $postComment = new ProjectPostComment();
        $postComment->setId(1);
        $postComment->setProjectPost($this->post1);
        $postComment->setUser($this->user);
        $postComment->setComment('new comment');

        $this->setExpectedException(\DSI\NotFound::class);
        $this->projectPostCommentRepo->save($postComment);
    }

    /** @test getAll */
    public function getAllComments()
    {
        $postComment = new \DSI\Entity\ProjectPostComment();
        $postComment->setUser($this->user);
        $postComment->setProjectPost($this->post1);
        $postComment->setComment($comment = 'new comment');
        $this->projectPostCommentRepo->insert($postComment);

        $this->assertCount(1, $this->projectPostCommentRepo->getAll());

        $postComment = new \DSI\Entity\ProjectPostComment();
        $postComment->setUser($this->user);
        $postComment->setProjectPost($this->post1);
        $postComment->setComment($comment = 'new comment');
        $this->projectPostCommentRepo->insert($postComment);

        $this->assertCount(2, $this->projectPostCommentRepo->getAll());
    }
}