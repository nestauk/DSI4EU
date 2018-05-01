<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository\LanguageRepo;
use \DSI\Entity\Language;

class LanguageRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var LanguageRepo */
    protected $languageRepo;

    public function setUp()
    {
        $this->languageRepo = new LanguageRepo;
    }

    public function tearDown()
    {
        $this->languageRepo->clearAll();
    }

    /** @test saveAsNew */
    public function languageCanBeSaved()
    {
        $language = new Language();
        $language->setName('test');

        $this->languageRepo->saveAsNew($language);

        $this->assertEquals(1, $language->getId());
    }

    /** @test save, getByID */
    public function languageCanBeUpdated()
    {
        $language = new Language();
        $language->setName('test');

        $this->languageRepo->saveAsNew($language);

        $language->setName('test2');
        $this->languageRepo->save($language);

        $sameSkil = $this->languageRepo->getById($language->getId());
        $this->assertEquals($language->getName(), $sameSkil->getName());
    }

    /** @test getByID */
    public function gettingAnNonExistentLanguageById_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->languageRepo->getById(1);
    }

    /** @test save */
    public function NonexistentLanguageCannotBeSaved()
    {
        $name = 'test';
        $language = new Language();
        $language->setId(1);
        $language->setName($name);

        $this->setExpectedException(\DSI\NotFound::class);
        $this->languageRepo->save($language);
    }

    /** @test getByName */
    public function userCanBeRetrievedByName()
    {
        $name = 'test';
        $language = new Language();
        $language->setName($name);
        $this->languageRepo->saveAsNew($language);

        $sameLanguage = $this->languageRepo->getByName($name);
        $this->assertEquals($language->getId(), $sameLanguage->getId());
    }

    /** @test getByID */
    public function gettingAnNonExistentLanguageByName_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->languageRepo->getByName('test');
    }

    /** @test nameExists */
    public function NonexistentUserCannotBeFoundByName()
    {
        $this->assertFalse($this->languageRepo->nameExists('test'));
    }

    /** @test saveAsNew */
    public function cannotSaveAsNewWithoutAName()
    {
        $language = new Language();
        $this->setExpectedException(\DSI\NotEnoughData::class);
        $this->languageRepo->saveAsNew($language);
    }

    /** @test save */
    public function cannotSaveWithAnEmptyName()
    {
        $language = new Language();
        $language->setName('test');
        $this->languageRepo->saveAsNew($language);

        $language->setName('');
        $this->setExpectedException(\DSI\NotEnoughData::class);
        $this->languageRepo->save($language);
    }

    /** @test save */
    public function cannotSaveAsNew2LanguagesWithTheSameName()
    {
        $language = new Language();
        $language->setName('test');
        $this->languageRepo->saveAsNew($language);

        $language = new Language();
        $language->setName('test');
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->languageRepo->saveAsNew($language);
    }

    /** @test save */
    public function cannotSave2LanguagesWithTheSameName()
    {
        $language_1 = new Language();
        $language_1->setName('test');
        $this->languageRepo->saveAsNew($language_1);

        $language_2 = new Language();
        $language_2->setName('test2');
        $this->languageRepo->saveAsNew($language_2);

        $language_2->setName('test');
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->languageRepo->save($language_2);
    }

    /** @test getAll */
    public function getAllLanguages()
    {
        $language = new Language();
        $language->setName('test');
        $this->languageRepo->saveAsNew($language);

        $this->assertCount(1, $this->languageRepo->getAll());

        $language = new Language();
        $language->setName('test2');
        $this->languageRepo->saveAsNew($language);

        $this->assertCount(2, $this->languageRepo->getAll());
    }

    /** @test saveAsNew getById */
    public function setAllLanguagesDetails()
    {
        $language = new Language();
        $language->setName('test');
        $this->languageRepo->saveAsNew($language);

        $sameLanguage = $this->languageRepo->getById($language->getId());
        $this->assertEquals($language->getName(), $sameLanguage->getName());
    }
}