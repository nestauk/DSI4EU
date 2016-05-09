<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository\TagForProjectsRepository;
use \DSI\Entity\TagForProjects;

class TagForProjectsRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var TagForProjectsRepository */
    protected $tagsRepo;

    public function setUp()
    {
        $this->tagsRepo = new TagForProjectsRepository();
    }

    public function tearDown()
    {
        $this->tagsRepo->clearAll();
    }

    /** @test saveAsNew */
    public function tagCanBeSaved()
    {
        $tag = new TagForProjects();
        $tag->setName('test');

        $this->tagsRepo->saveAsNew($tag);

        $this->assertEquals(1, $tag->getId());
    }

    /** @test save, getByID */
    public function tagCanBeUpdated()
    {
        $tag = new TagForProjects();
        $tag->setName('test');

        $this->tagsRepo->saveAsNew($tag);

        $tag->setName('test2');
        $this->tagsRepo->save($tag);

        $sameTag = $this->tagsRepo->getById($tag->getId());
        $this->assertEquals($tag->getName(), $sameTag->getName());
    }

    /** @test getByID */
    public function gettingAnNonExistentTagById_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->tagsRepo->getById(1);
    }

    /** @test save */
    public function NonexistentTagCannotBeSaved()
    {
        $name = 'test';
        $tag = new TagForProjects();
        $tag->setId(1);
        $tag->setName($name);

        $this->setExpectedException(\DSI\NotFound::class);
        $this->tagsRepo->save($tag);
    }

    /** @test getByName */
    public function tagCanBeRetrievedByName()
    {
        $name = 'test';
        $tag = new TagForProjects();
        $tag->setName($name);
        $this->tagsRepo->saveAsNew($tag);

        $sameTag = $this->tagsRepo->getByName($name);
        $this->assertEquals($tag->getId(), $sameTag->getId());
    }

    /** @test getByID */
    public function gettingAnNonExistentTagByName_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->tagsRepo->getByName('test');
    }

    /** @test nameExists */
    public function NonexistentTagCannotBeFoundByName()
    {
        $this->assertFalse($this->tagsRepo->nameExists('test'));
    }

    /** @test saveAsNew */
    public function cannotSaveAsNewWithoutAName()
    {
        $tag = new TagForProjects();
        $this->setExpectedException(\DSI\NotEnoughData::class);
        $this->tagsRepo->saveAsNew($tag);
    }

    /** @test save */
    public function cannotSaveWithAnEmptyName()
    {
        $tag = new TagForProjects();
        $tag->setName('test');
        $this->tagsRepo->saveAsNew($tag);

        $tag->setName('');
        $this->setExpectedException(\DSI\NotEnoughData::class);
        $this->tagsRepo->save($tag);
    }

    /** @test save */
    public function cannotSaveAsNew2TagsWithTheSameName()
    {
        $tag = new TagForProjects();
        $tag->setName('test');
        $this->tagsRepo->saveAsNew($tag);

        $tag = new TagForProjects();
        $tag->setName('test');
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->tagsRepo->saveAsNew($tag);
    }

    /** @test save */
    public function cannotSave2TagsWithTheSameName()
    {
        $tag_1 = new TagForProjects();
        $tag_1->setName('test');
        $this->tagsRepo->saveAsNew($tag_1);

        $tag_2 = new TagForProjects();
        $tag_2->setName('test2');
        $this->tagsRepo->saveAsNew($tag_2);

        $tag_2->setName('test');
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->tagsRepo->save($tag_2);
    }

    /** @test getAll */
    public function getAllTags()
    {
        $tags = new TagForProjects();
        $tags->setName('test');
        $this->tagsRepo->saveAsNew($tags);

        $this->assertCount(1, $this->tagsRepo->getAll());

        $tags = new TagForProjects();
        $tags->setName('test2');
        $this->tagsRepo->saveAsNew($tags);

        $this->assertCount(2, $this->tagsRepo->getAll());
    }

    /** @test saveAsNew getById */
    public function setAllTagsDetails()
    {
        $tag = new TagForProjects();
        $tag->setName('test');
        $this->tagsRepo->saveAsNew($tag);

        $sameTag = $this->tagsRepo->getById($tag->getId());
        $this->assertEquals($tag->getName(), $sameTag->getName());
    }
}