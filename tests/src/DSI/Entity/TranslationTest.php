<?php

use DSI\Entity\Translation;

require_once __DIR__ . '/../../../config.php';

class TranslationTest extends \PHPUnit_Framework_TestCase
{
    /** @var Translation */
    private $translation;

    public function setUp()
    {
        $this->translation = new Translation();
    }

    /** @test */
    public function settingAnIndex_returnsTheIndex()
    {
        $this->translation->setIndex($index = 'Test Index');
        $this->assertEquals($index, $this->translation->getIndex());
    }

    /** @test */
    public function settingDetails_returnsDetails()
    {
        $details = 'Used not so often';
        $this->translation->setDetails($details);
        $this->assertEquals($details, $this->translation->getDetails());
    }

    /** @test */
    public function settingTranslations_returnsTranslations()
    {
        foreach (Translation::LANGUAGES AS $lang) {
            $this->translation->setTranslationFor($lang, $translation = 'word for ' . $lang);
            $this->assertEquals($translation, $this->translation->getTranslationFor($lang));
        }
    }

    /** @test */
    public function cannotGetTranslationForInvalidLanguage()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->translation->getTranslationFor('rou');
    }
}