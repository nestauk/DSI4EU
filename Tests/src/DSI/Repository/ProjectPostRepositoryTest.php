<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository\UserRepository;
use \DSI\Repository\ProjectRepository;
use \DSI\Repository\ProjectPostRepository;
use \DSI\Entity\Project;
use \DSI\Entity\User;

class ProjectPostRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var ProjectPostRepository */
    private $projectPostRepo;

    /** @var ProjectRepository */
    private $projectRepo;

    /** @var UserRepository */
    private $userRepo;

    /** @var Project */
    private $project1, $project2;

    /** @var User */
    private $user1, $user2;

    public function setUp()
    {
        $this->projectPostRepo = new ProjectPostRepository();
        $this->projectRepo = new ProjectRepository();
        $this->userRepo = new UserRepository();

        $this->user1 = new User();
        $this->user2 = new User();
        $this->userRepo->insert($this->user1);
        $this->userRepo->insert($this->user2);

        $this->project1 = new Project();
        $this->project1->setOwner($this->user1);
        $this->project2 = new Project();
        $this->project2->setOwner($this->user2);
        $this->projectRepo->insert($this->project1);
        $this->projectRepo->insert($this->project2);
    }

    public function tearDown()
    {
        $this->projectPostRepo->clearAll();
        $this->projectRepo->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test saveAsNew */
    public function postCanBeSaved()
    {
        $post = new \DSI\Entity\ProjectPost();
        $post->setProject($this->project1);
        $post->setUser($this->user1);
        $this->projectPostRepo->insert($post);

        $this->assertEquals(1, $post->getId());
    }

    /** @test save, getByID */
    public function projectCanBeUpdated()
    {
        $post = new \DSI\Entity\ProjectPost();
        $post->setProject($this->project1);
        $post->setUser($this->user1);
        $this->projectPostRepo->insert($post);

        $post->setProject($this->project2);
        $post->setUser($this->user2);
        $this->projectPostRepo->save($post);

        $samePost = $this->projectPostRepo->getById($post->getId());
        $this->assertEquals($this->project2->getId(), $samePost->getProject()->getId());
        $this->assertEquals($this->user2->getId(), $samePost->getUser()->getId());
    }

    /** @test getByID */
    public function gettingAnNonExistentPostById_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->projectPostRepo->getById(1);
    }

    /** @test save */
    public function NonexistentPostCannotBeSaved()
    {
        $post = new \DSI\Entity\ProjectPost();
        $post->setId(1);
        $post->setProject($this->project1);
        $post->setUser($this->user1);

        $this->setExpectedException(\DSI\NotFound::class);
        $this->projectPostRepo->save($post);
    }

    /** @test getAll */
    public function getAllPosts()
    {
        $post = new \DSI\Entity\ProjectPost();
        $post->setProject($this->project1);
        $post->setUser($this->user1);
        $this->projectPostRepo->insert($post);

        $this->assertCount(1, $this->projectPostRepo->getAll());

        $post = new \DSI\Entity\ProjectPost();
        $post->setProject($this->project1);
        $post->setUser($this->user1);
        $this->projectPostRepo->insert($post);

        $this->assertCount(2, $this->projectPostRepo->getAll());
    }

    /** @test saveAsNew getById */
    public function setAllPostDetails()
    {
        $post = new \DSI\Entity\ProjectPost();
        $post->setProject($this->project1);
        $post->setUser($this->user1);
        $post->setTitle($title = 'Post Title');
        $post->setText($text = 'Post Text');
        $this->projectPostRepo->insert($post);

        $post = $this->projectPostRepo->getById($post->getId());
        $this->assertEquals($this->project1->getId(), $post->getProject()->getId());
        $this->assertEquals($this->user1->getId(), $post->getUser()->getId());
        $this->assertEquals($title, $post->getTitle());
        $this->assertEquals($text, $post->getText());
        $this->assertRegExp('[^\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}:\d{2}$]', $post->getTime());
    }
}