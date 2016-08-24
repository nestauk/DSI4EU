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
        $this->addLanguageToUser($this->language_1, $this->user_1);
        $this->addLanguageToUser($this->language_2, $this->user_1);
        $this->addLanguageToUser($this->language_1, $this->user_2);
        $this->addLanguageToUser($this->language_1, $this->user_3);

        $this->assertCount(2, $this->userLanguageRepo->getByUserID($this->user_1->getId()));
        $this->assertCount(1, $this->userLanguageRepo->getByUserID($this->user_2->getId()));
        $this->assertCount(1, $this->userLanguageRepo->getByUserID($this->user_3->getId()));
    }

    /** @test saveAsNew */
    public function cannotAddSameUserLanguageTwice()
    {
        $this->addLanguageToUser($this->language_1, $this->user_1);
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->addLanguageToUser($this->language_1, $this->user_1);
    }

    /** @test saveAsNew */
    public function getAllLanguageIDsForUser()
    {
        $this->addLanguageToUser($this->language_1, $this->user_1);
        $this->addLanguageToUser($this->language_2, $this->user_1);

        $this->assertEquals([1, 2], $this->userLanguageRepo->getLanguageIDsForUser(1));
    }

    /** @test saveAsNew */
    public function getAllUsersForLanguage()
    {
        $this->addLanguageToUser($this->language_1, $this->user_1);
        $this->addLanguageToUser($this->language_1, $this->user_2);

        $this->assertCount(2, $this->userLanguageRepo->getByLanguageID(1));
    }

    /** @test saveAsNew */
    public function getAllUserIDsForLanguage()
    {
        $this->addLanguageToUser($this->language_1, $this->user_1);
        $this->addLanguageToUser($this->language_1, $this->user_2);

        $this->assertEquals([1, 2], $this->userLanguageRepo->getUserIDsForLanguage(1));
    }

    /** @test saveAsNew */
    public function canCheckIfUserHasLanguageName()
    {
        $this->addLanguageToUser($this->language_1, $this->user_1);
        $this->addLanguageToUser($this->language_2, $this->user_2);

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
        $this->addLanguageToUser($this->language_1, $this->user_1);
        $this->addLanguageToUser($this->language_2, $this->user_1);
        $this->addLanguageToUser($this->language_3, $this->user_2);

        $this->assertEquals(
            [$this->language_1->getName(), $this->language_2->getName()],
            $this->userLanguageRepo->getLanguagesNameByUserID($this->user_1->getId())
        );
        $this->assertEquals(
            [$this->language_3->getName()],
            $this->userLanguageRepo->getLanguagesNameByUserID($this->user_2->getId())
        );
    }

    /** @test */
    public function canRemoveLanguageFromUser()
    {
        $this->addLanguageToUser($this->language_1, $this->user_1);
        $this->addLanguageToUser($this->language_2, $this->user_1);
        $this->addLanguageToUser($this->language_3, $this->user_1);

        $this->assertCount(3, $this->userLanguageRepo->getByUserID($this->user_1->getId()));

        $this->removeLanguageFromUser($this->language_3, $this->user_1);

        $this->assertCount(2, $this->userLanguageRepo->getByUserID($this->user_1->getId()));
    }

    /** @test */
    public function cannotRemoveNonexistentLanguageFromUser()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->removeLanguageFromUser($this->language_3, $this->user_1);
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

    /**
     * @param $language
     * @param $user
     */
    private function addLanguageToUser($language, $user)
    {
        $userLanguage = new \DSI\Entity\UserLanguage();
        $userLanguage->setLanguage($language);
        $userLanguage->setUser($user);
        $this->userLanguageRepo->add($userLanguage);
    }

    /**
     * @param $language
     * @param $user
     */
    private function removeLanguageFromUser($language, $user)
    {
        $userLanguage = new \DSI\Entity\UserLanguage();
        $userLanguage->setLanguage($language);
        $userLanguage->setUser($user);
        $this->userLanguageRepo->remove($userLanguage);
    }
}