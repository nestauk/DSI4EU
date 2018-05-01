<?php

require_once __DIR__ . '/../../../config.php';

class RemoveLanguageFromUserTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\AddLanguageToUser */
    private $addLanguageToUserCommand;

    /** @var \DSI\UseCase\RemoveLanguageFromUser */
    private $removeLanguageFromUserCommand;

    /** @var \DSI\Repository\LanguageRepo */
    private $languageRepo;

    /** @var \DSI\Repository\UserRepo */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user;

    public function setUp()
    {
        $this->addLanguageToUserCommand = new \DSI\UseCase\AddLanguageToUser();
        $this->removeLanguageFromUserCommand = new \DSI\UseCase\RemoveLanguageFromUser();
        $this->languageRepo = new \DSI\Repository\LanguageRepo();
        $this->userRepo = new \DSI\Repository\UserRepo();

        $this->user = new \DSI\Entity\User();
        $this->userRepo->insert($this->user);

        $language = new \DSI\Entity\Language();
        $language->setName('English');
        $this->languageRepo->saveAsNew($language);
    }

    public function tearDown()
    {
        $this->languageRepo->clearAll();
        $this->userRepo->clearAll();
        (new \DSI\Repository\UserLanguageRepo())->clearAll();
    }

    /** @test */
    public function successfulRemoveLanguageFromUser()
    {
        $this->addLanguageToUserCommand->data()->language = 'English';
        $this->addLanguageToUserCommand->data()->userID = $this->user->getId();
        $this->addLanguageToUserCommand->exec();

        $this->removeLanguageFromUserCommand->data()->language = 'English';
        $this->removeLanguageFromUserCommand->data()->userID = $this->user->getId();
        $this->removeLanguageFromUserCommand->exec();

        $this->assertFalse(
            (new \DSI\Repository\UserLanguageRepo())->userHasLanguageName(
                $this->removeLanguageFromUserCommand->data()->userID,
                $this->removeLanguageFromUserCommand->data()->language
            )
        );
    }

    /** @test */
    public function cannotRemoveIfUserDoesNotHaveIt()
    {
        $e = null;

        try {
            $this->removeLanguageFromUserCommand->data()->language = 'test';
            $this->removeLanguageFromUserCommand->data()->userID = $this->user->getId();
            $this->removeLanguageFromUserCommand->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('language'));
    }
}