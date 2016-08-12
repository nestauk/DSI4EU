<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository\TranslationRepository;
use \DSI\Entity\Translation;

class TranslationRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var TranslationRepository */
    private $translationRepository;

    public function setUp()
    {
        $this->translationRepository = new TranslationRepository();
    }

    public function tearDown()
    {
        $this->translationRepository->clearAll();
    }

    /** @test */
    public function canBeAdded()
    {
        $translation = new Translation();
        $translation->setIndex($index = 'Use Cases');
        $translation->setDetails($details = 'Use Cases Details');
        foreach (Translation::LANGUAGES AS $lang)
            $translation->setTranslationFor($lang, 'Translation for ' . $lang);

        $this->translationRepository->insert($translation);
        $translation = $this->translationRepository->getByIndex($index);

        $this->assertEquals($index, $translation->getIndex());
        $this->assertEquals($details, $translation->getDetails());
        foreach (Translation::LANGUAGES AS $lang)
            $this->assertEquals('Translation for ' . $lang, $translation->getTranslationFor($lang));
    }

    /** @test */
    public function canBeUpdated()
    {
        $translation = new Translation();
        $translation->setIndex($index = 'Use Cases');
        $this->translationRepository->insert($translation);

        $translation->setDetails($details = 'Use Cases Details');
        foreach (Translation::LANGUAGES AS $lang)
            $translation->setTranslationFor($lang, 'Translation for ' . $lang);

        $this->translationRepository->save($translation);
        $translation = $this->translationRepository->getByIndex($index);

        $this->assertEquals($index, $translation->getIndex());
        $this->assertEquals($details, $translation->getDetails());
        foreach (Translation::LANGUAGES AS $lang)
            $this->assertEquals('Translation for ' . $lang, $translation->getTranslationFor($lang));
    }

    /** @test */
    public function cannotSaveNonexistentIndex()
    {
        $translation = new Translation();
        $translation->setIndex($index = 'Use Cases');
        $this->setExpectedException(\DSI\NotFound::class);
        $this->translationRepository->save($translation);
    }

    /** @test */
    public function cannotInsertSameIndexTwice()
    {
        $translation = new Translation();
        $translation->setIndex($index = 'Use Cases');
        $this->translationRepository->insert($translation);

        $translation = new Translation();
        $translation->setIndex($index = 'Use Cases');
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->translationRepository->insert($translation);
    }

    /** @test */
    public function cannotGetByNonexistentIndex()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->translationRepository->getByIndex('Non Existent Index');
    }

    /** @test */
    public function canGetAllTranslations()
    {
        $this->assertCount(0, $this->translationRepository->getAll());

        $translation = new Translation();
        $translation->setIndex($index = 'Use Case 1');
        $this->translationRepository->insert($translation);

        $this->assertCount(1, $this->translationRepository->getAll());

        $translation = new Translation();
        $translation->setIndex($index = 'Use Case 2');
        $this->translationRepository->insert($translation);

        $this->assertCount(2, $this->translationRepository->getAll());

        $translation = new Translation();
        $translation->setIndex($index = 'Use Case 3');
        $this->translationRepository->insert($translation);

        $this->assertCount(3, $this->translationRepository->getAll());
    }
}