<?php

namespace DSI\UseCase;

use abeautifulsite\SimpleImage;
use DSI\NotEnoughData;
use DSI\NotFound;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class UpdateUserProfilePicture
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UpdateUserProfilePicture_Data */
    private $data;

    /** @var UserRepository */
    private $userRepo;

    /** @var string */
    private $profilePic;

    public function __construct()
    {
        $this->data = new UpdateUserProfilePicture_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->userRepo = new UserRepository();

        $this->checkIfAllTheInfoHaveBeenSent();
        $this->checkIfFileExistsOnServer();
        $this->checkFilename();

        $fileInfo = new \SplFileInfo($this->data()->fileName);

        $this->checkFileExtension($fileInfo);

        $img = new SimpleImage($this->data()->filePath);

        $this->checkImageDimensions($img);

        $img->thumbnail(100, 100)->save($this->data()->filePath, null, $fileInfo->getExtension());

        $this->profilePic = $this->data()->userID . '-' . $this->data()->fileName;
        rename($this->data()->filePath, __DIR__ . '/../../../www/images/users/profile/' . $this->profilePic);

        $this->updateUserDetails();
    }

    /**
     * @return UpdateUserProfilePicture_Data
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getProfilePic()
    {
        return $this->profilePic;
    }

    private function checkFileExtension($fileInfo)
    {
        if (!in_array(strtolower($fileInfo->getExtension()), [
            'jpg', 'jpeg', 'png'
        ])
        ) {
            $this->errorHandler->addTaggedError('file', 'Only image files accepted (received .' . $fileInfo->getExtension() . ')');
            $this->errorHandler->throwIfNotEmpty();
        }
    }

    private function checkFilename()
    {
        if (basename($this->data()->fileName) != $this->data()->fileName) {
            $this->errorHandler->addTaggedError('file', 'Invalid file name. Try renaming the file');
            $this->errorHandler->throwIfNotEmpty();
        }
    }

    private function checkIfFileExistsOnServer()
    {
        if (!file_exists($this->data()->filePath))
            throw new NotFound('filePath');
    }

    private function checkIfAllTheInfoHaveBeenSent()
    {
        if (!isset($this->data()->filePath))
            throw new NotEnoughData('filePath');
        if (!isset($this->data()->fileName))
            throw new NotEnoughData('fileName');
        if (!isset($this->data()->userID))
            throw new NotEnoughData('userID');
    }

    /**
     * @param $img
     * @throws ErrorHandler
     */
    private function checkImageDimensions($img)
    {
        if ($img->get_height() < 100 OR $img->get_width() < 100) {
            $this->errorHandler->addTaggedError('file', 'Image must be at least 100x100');
            $this->errorHandler->throwIfNotEmpty();
        }
    }

    private function updateUserDetails()
    {
        $user = $this->userRepo->getById($this->data()->userID);
        $user->setProfilePic($this->profilePic);
        $this->userRepo->save($user);
    }
}

class UpdateUserProfilePicture_Data
{
    /** @var string */
    public $filePath,
        $fileName;

    /** @var int */
    public $userID;
}