<?php

require_once __DIR__ . '/../../../config.php';

class AddLanguageToUserTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\AddLanguageToUser */
    private $addLanguageToUserCommand;

    /** @var \DSI\Repository\LanguageRepository */
    private $languageRepo;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user;

    public function setUp()
    {
        $this->addLanguageToUserCommand = new \DSI\UseCase\AddLanguageToUser();
        $this->languageRepo = new \DSI\Repository\LanguageRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();

        $this->user = new \DSI\Entity\User();
        $this->userRepo->saveAsNew($this->user);

        $language = new \DSI\Entity\Language();
        $language->setName('English');
        $this->languageRepo->saveAsNew($language);
    }

    public function tearDown()
    {
        $this->languageRepo->clearAll();
        $this->userRepo->clearAll();
        (new \DSI\Repository\UserLanguageRepository())->clearAll();
    }

    /** @test */
    public function successfulAdditionOfLanguageToUser()
    {
        $this->addLanguageToUserCommand->data()->language = 'English';
        $this->addLanguageToUserCommand->data()->userID = $this->user->getId();
        $this->addLanguageToUserCommand->exec();

        $this->assertTrue(
            (new \DSI\Repository\UserLanguageRepository())->userHasLanguageName(
                $this->addLanguageToUserCommand->data()->userID,
                $this->addLanguageToUserCommand->data()->language
            )
        );
    }

    /** @test */
    public function cannotAddSameLanguageTwice()
    {
        try {
            $e = null;
            $this->addLanguageToUserCommand->data()->language = 'English';
            $this->addLanguageToUserCommand->data()->userID = $this->user->getId();
            $this->addLanguageToUserCommand->exec();

            $this->addLanguageToUserCommand->data()->language = 'English';
            $this->addLanguageToUserCommand->data()->userID = $this->user->getId();
            $this->addLanguageToUserCommand->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('language'));
    }
}