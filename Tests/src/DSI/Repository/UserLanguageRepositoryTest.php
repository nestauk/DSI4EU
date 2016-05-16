<?php

require_once __DIR__ . '/../../../config.php';

class UserLanguageRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Repository\UserLanguageRepository */
    protected $userLanguageRepo;

    /** @var \DSI\Repository\UserRepository */
    protected $userRepo;

    /** @var \DSI\Entity\User */
    protected $user_1, $user_2, $user_3;

    /** @var \DSI\Repository\LanguageRepository */
    protected $languageRepo;

    /** @var \DSI\Entity\Language */
    protected $language_1, $language_2, $language_3;

    public function setUp()
    {
        $this->userLanguageRepo = new \DSI\Repository\UserLanguageRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();
        $this->languageRepo = new \DSI\Repository\LanguageRepository();

        $this->user_1 = $this->createUser(1);
        $this->user_2 = $this->createUser(2);
        $this->user_3 = $this->createUser(3);
        $this->language_1 = $this->createLanguage(1);
        $this->language_2 = $this->createLanguage(2);
        $this->language_3 = $this->createLanguage(3);
    }

    public function tearDown()
    {
        $this->userLanguageRepo->clearAll();
        $this->userRepo->clearAll();
        $this->languageRepo->clearAll();
    }

    /** @test saveAsNew */
    public function userLanguageCanBeSaved()
    {
        $userLanguage = new \DSI\Entity\UserLanguage();
        $userLanguage->setUser($this->user_1);
        $userLanguage->setLanguage($this->language_1);
        $this->userLanguageRepo->add($userLanguage);

        $userLanguage = new \DSI\Entity\UserLanguage();
        $userLanguage->setUser($this->user_1);
        $userLanguage->setLanguage($this->language_2);
        $this->userLanguageRepo->add($userLanguage);

        $userLanguage = new \DSI\Entity\UserLanguage();
        $userLanguage->setUser($this->user_2);
        $userLanguage->setLanguage($this->language_1);
        $this->userLanguageRepo->add($userLanguage);

        $userLanguage = new \DSI\Entity\UserLanguage();
        $userLanguage->setUser($this->user_3);
        $userLanguage->setLanguage($this->language_1);
        $this->userLanguageRepo->add($userLanguage);

        $this->assertCount(2, $this->userLanguageRepo->getByUserID(1));
    }

    /** @test saveAsNew */
    public function cannotAddSameUserLanguageTwice()
    {
        $userLanguage = new \DSI\Entity\UserLanguage();
        $userLanguage->setUser($this->user_1);
        $userLanguage->setLanguage($this->language_1);
        $this->userLanguageRepo->add($userLanguage);

        $userLanguage = new \DSI\Entity\UserLanguage();
        $userLanguage->setUser($this->user_1);
        $userLanguage->setLanguage($this->language_1);
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->userLanguageRepo->add($userLanguage);
    }

    /** @test saveAsNew */
    public function getAllLanguageIDsForUser()
    {
        $userLanguage = new \DSI\Entity\UserLanguage();
        $userLanguage->setUser($this->user_1);
        $userLanguage->setLanguage($this->language_1);
        $this->userLanguageRepo->add($userLanguage);

        $userLanguage = new \DSI\Entity\UserLanguage();
        $userLanguage->setUser($this->user_1);
        $userLanguage->setLanguage($this->language_2);
        $this->userLanguageRepo->add($userLanguage);

        $this->assertEquals([1, 2], $this->userLanguageRepo->getLanguageIDsForUser(1));
    }

    /** @test saveAsNew */
    public function getAllUsersForLanguage()
    {
        $userLanguage = new \DSI\Entity\UserLanguage();
        $userLanguage->setUser($this->user_1);
        $userLanguage->setLanguage($this->language_1);
        $this->userLanguageRepo->add($userLanguage);

        $userLanguage = new \DSI\Entity\UserLanguage();
        $userLanguage->setUser($this->user_2);
        $userLanguage->setLanguage($this->language_1);
        $this->userLanguageRepo->add($userLanguage);

        $this->assertCount(2, $this->userLanguageRepo->getByLanguageID(1));
    }

    /** @test saveAsNew */
    public function getAllUserIDsForLanguage()
    {
        $userLanguage = new \DSI\Entity\UserLanguage();
        $userLanguage->setUser($this->user_1);
        $userLanguage->setLanguage($this->language_1);
        $this->userLanguageRepo->add($userLanguage);

        $userLanguage = new \DSI\Entity\UserLanguage();
        $userLanguage->setUser($this->user_2);
        $userLanguage->setLanguage($this->language_1);
        $this->userLanguageRepo->add($userLanguage);

        $this->assertEquals([1, 2], $this->userLanguageRepo->getUserIDsForLanguage(1));
    }

    /** @test saveAsNew */
    public function canCheckIfUserHasLanguageName()
    {
        $userLanguage = new \DSI\Entity\UserLanguage();
        $userLanguage->setUser($this->user_1);
        $userLanguage->setLanguage($this->language_1);
        $this->userLanguageRepo->add($userLanguage);

        $userLanguage = new \DSI\Entity\UserLanguage();
        $userLanguage->setUser($this->user_2);
        $userLanguage->setLanguage($this->language_2);
        $this->userLanguageRepo->add($userLanguage);

        $this->assertTrue($this->userLanguageRepo->userHasLanguageName(
            $this->user_1->getId(), $this->language_1->getName())
        );
        $this->assertFalse($this->userLanguageRepo->userHasLanguageName(
            $this->user_1->getId(), $this->language_2->getName())
        );
        $this->assertTrue($this->userLanguageRepo->userHasLanguageName(
            $this->user_2->getId(), $this->language_2->getName())
        );
        $this->assertFalse($this->userLanguageRepo->userHasLanguageName(
            $this->user_2->getId(), $this->language_1->getName())
        );
    }

    /** @test saveAsNew */
    public function canGetLanguageNamesByUserID()
    {
        $userLanguage = new \DSI\Entity\UserLanguage();
        $userLanguage->setUser($this->user_1);
        $userLanguage->setLanguage($this->language_1);
        $this->userLanguageRepo->add($userLanguage);

        $userLanguage = new \DSI\Entity\UserLanguage();
        $userLanguage->setUser($this->user_1);
        $userLanguage->setLanguage($this->language_2);
        $this->userLanguageRepo->add($userLanguage);

        $userLanguage = new \DSI\Entity\UserLanguage();
        $userLanguage->setUser($this->user_2);
        $userLanguage->setLanguage($this->language_3);
        $this->userLanguageRepo->add($userLanguage);

        $this->assertEquals(
            [$this->language_1->getName(), $this->language_2->getName()],
            $this->userLanguageRepo->getLanguagesNameByUserID($this->user_1->getId())
        );
        $this->assertEquals(
            [$this->language_3->getName()],
            $this->userLanguageRepo->getLanguagesNameByUserID($this->user_2->getId())
        );
    }


    private function createUser(int $userID)
    {
        $user = new \DSI\Entity\User();
        $user->setId($userID);
        $this->userRepo->insert($user);
        return $user;
    }

    private function createLanguage(int $languageID)
    {
        $language = new \DSI\Entity\Language();
        $language->setId($languageID);
        $language->setName('language-' . $languageID);
        $this->languageRepo->saveAsNew($language);
        return $language;
    }
}