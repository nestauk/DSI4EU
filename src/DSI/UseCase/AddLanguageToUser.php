<?php

namespace DSI\UseCase;

use DSI\Entity\UserLanguage;
use DSI\Repository\LanguageRepo;
use DSI\Repository\UserRepo;
use DSI\Repository\UserLanguageRepo;
use DSI\Service\ErrorHandler;

class AddLanguageToUser
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UserLanguageRepo */
    private $userLanguageRepo;

    /** @var AddLanguageToUser_Data */
    private $data;

    public function __construct()
    {
        $this->data = new AddLanguageToUser_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->userLanguageRepo = new UserLanguageRepo();

        $languageRepo = new LanguageRepo();
        $userRepo = new UserRepo();

        $language = null;
        if ($languageRepo->nameExists($this->data()->language)) {
            $language = $languageRepo->getByName($this->data()->language);
        } else {
            $this->errorHandler->addTaggedError('language', 'This language does not exist: ' . $this->data()->language);
            $this->errorHandler->throwIfNotEmpty();
        }

        if($this->userLanguageRepo->userHasLanguageName($this->data()->userID, $this->data()->language)) {
            $this->errorHandler->addTaggedError('language', 'User already has this language');
            $this->errorHandler->throwIfNotEmpty();
        }
            
        $userLanguage = new UserLanguage();
        $userLanguage->setLanguage($language);
        $userLanguage->setUser( $userRepo->getById($this->data()->userID) );
        $this->userLanguageRepo->add($userLanguage);
    }

    /**
     * @return AddLanguageToUser_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class AddLanguageToUser_Data
{
    /** @var string */
    public $language;

    /** @var int */
    public $userID;
}