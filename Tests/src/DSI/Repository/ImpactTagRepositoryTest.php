<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository\ImpactTagRepository;
use \DSI\Entity\ImpactTag;

class ImpactTagRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var ImpactTagRepository */
    protected $tagsRepo;

    public function setUp()
    {
        $this->tagsRepo = new ImpactTagRepository();
    }

    public function tearDown()
    {
        $this->tagsRepo->clearAll();
    }

    /** @test saveAsNew */
    public function tagCanBeSaved()
    {
        $tag = new ImpactTag();
        $tag->setName('test');

        $this->tagsRepo->insert($tag);

        $this->assertEquals(1, $tag->getId());
    }

    /** @test save, getByID */
    public function tagCanBeUpdated()
    {
        $tag = new ImpactTag();
        $tag->setName('test');

        $this->tagsRepo->insert($tag);

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
        $tag = new ImpactTag();
        $tag->setId(1);
        $tag->setName($name);

        $this->setExpectedException(\DSI\NotFound::class);
        $this->tagsRepo->save($tag);
    }

    /** @test getByName */
    public function tagCanBeRetrievedByName()
    {
        $name = 'test';
        $tag = new ImpactTag();
        $tag->setName($name);
        $this->tagsRepo->insert($tag);

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
        $tag = new ImpactTag();
        $this->setExpectedException(\DSI\NotEnoughData::class);
        $this->tagsRepo->insert($tag);
    }

    /** @test save */
    public function cannotSaveWithAnEmptyName()
    {
        $tag = new ImpactTag();
        $tag->setName('test');
        $this->tagsRepo->insert($tag);

        $tag->setName('');
        $this->setExpectedException(\DSI\NotEnoughData::class);
        $this->tagsRepo->save($tag);
    }

    /** @test save */
    public function cannotSaveAsNew2TagsWithTheSameName()
    {
        $tag = new ImpactTag();
        $tag->setName('test');
        $this->tagsRepo->insert($tag);

        $tag = new ImpactTag();
        $tag->setName('test');
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->tagsRepo->insert($tag);
    }

    /** @test save */
    public function cannotSave2TagsWithTheSameName()
    {
        $tag_1 = new ImpactTag();
        $tag_1->setName('test');
        $this->tagsRepo->insert($tag_1);

        $tag_2 = new ImpactTag();
        $tag_2->setName('test2');
        $this->tagsRepo->insert($tag_2);

        $tag_2->setName('test');
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->tagsRepo->save($tag_2);
    }

    /** @test getAll */
    public function getAllTags()
    {
        $tags = new ImpactTag();
        $tags->setName('test');
        $this->tagsRepo->insert($tags);

        $this->assertCount(1, $this->tagsRepo->getAll());

        $tags = new ImpactTag();
        $tags->setName('test2');
        $this->tagsRepo->insert($tags);

        $this->assertCount(2, $this->tagsRepo->getAll());
    }

    /** @test saveAsNew getById */
    public function setAllTagsDetails()
    {
        $tag = new ImpactTag();
        $tag->setName('test');
        $this->tagsRepo->insert($tag);

        $sameTag = $this->tagsRepo->getById($tag->getId());
        $this->assertEquals($tag->getName(), $sameTag->getName());
    }
}