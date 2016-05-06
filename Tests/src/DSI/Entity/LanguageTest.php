<?php

use DSI\Entity\Language;

require_once __DIR__ . '/../../../config.php';

class LanguageTest extends \PHPUnit_Framework_TestCase
{
    /** @var Language */
    private $language;

    public function setUp()
    {
        $this->language = new Language();
    }

    /** @test setId, getId */
    public function settingAnId_returnsTheId()
    {
        $this->language->setId(1);
        $this->assertEquals(1, $this->language->getId());
    }

    /** @test setId */
    public function settingAnInvalidId_throwsException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->language->setId(0);
    }

    /** @test setLanguage, getLanguage*/
    public function settingAName_returnsTheName()
    {
        $name = 'php';
        $this->language->setName($name);
        $this->assertEquals($name, $this->language->getName());
    }
}