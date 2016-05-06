<?php

use DSI\Entity\UserLanguage;

require_once __DIR__ . '/../../../config.php';

class UserLanguageTest extends \PHPUnit_Framework_TestCase
{
    /** @var UserLanguage */
    private $userLanguage;

    public function setUp()
    {
        $this->userLanguage = new UserLanguage();
    }

    /** @test setId, getId */
    public function settingDetails_returnsTheDetails()
    {
        $user = new \DSI\Entity\User();
        $user->setId(1);
        $language = new \DSI\Entity\Language();
        $language->setId(1);

        $this->userLanguage = new UserLanguage();
        $this->userLanguage->setUser($user);
        $this->userLanguage->setLanguage($language);

        $this->assertEquals($user->getId(), $this->userLanguage->getUserID());
        $this->assertEquals($user->getId(), $this->userLanguage->getUser()->getId());
        $this->assertEquals($language->getId(), $this->userLanguage->getLanguageID());
        $this->assertEquals($language->getId(), $this->userLanguage->getLanguage()->getId());
    }
}