<?php

namespace DSI\UseCase;

use DSI\Entity\UserLanguage;
use DSI\Repository\LanguageRepository;
use DSI\Repository\UserRepository;
use DSI\Repository\UserLanguageRepository;
use DSI\Service\ErrorHandler;

class RemoveLanguageFromUser
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UserLanguageRepository */
    private $userLanguageRepo;

    /** @var RemoveLanguageFromUser_Data */
    private $data;

    public function __construct()
    {
        $this->data = new RemoveLanguageFromUser_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->userLanguageRepo = new UserLanguageRepository();

        $languageRepo = new LanguageRepository();
        $userRepo = new UserRepository();

        $language = null;
        if ($languageRepo->nameExists($this->data()->language)) {
            $language = $languageRepo->getByName($this->data()->language);
        } else {
            $this->errorHandler->addTaggedError('language', 'This language does not exist: ' . $this->data()->language);
            $this->errorHandler->throwIfNotEmpty();
        }

        if (!$this->userLanguageRepo->userHasLanguageName($this->data()->userID, $this->data()->language)) {
            $this->errorHandler->addTaggedError('language', 'User does not have this language');
            $this->errorHandler->throwIfNotEmpty();
        }

        $userLanguage = new UserLanguage();
        $userLanguage->setLanguage($language);
        $userLanguage->setUser($userRepo->getById($this->data()->userID));
        $this->userLanguageRepo->remove($userLanguage);
    }

    /**
     * @return RemoveLanguageFromUser_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class RemoveLanguageFromUser_Data
{
    /** @var string */
    public $language;

    /** @var int */
    public $userID;
}