<?php

namespace DSI\UseCase;

use abeautifulsite\SimpleImage;
use DSI\Entity\Image;
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

        $this->assertAllInfoHaveBeenSent();

        $orgFileName = basename($this->data()->fileName);
        $orgFilePath = Image::TEMP_FOLDER . $orgFileName;
        $newFileName = $this->data()->userID . '-' . $orgFileName;
        $newFilePath = Image::PROFILE_PIC . $newFileName;

        $this->assertFileExistsOnServer($orgFilePath);

        $fileInfo = new \SplFileInfo($orgFilePath);
        $this->checkFileExtension($fileInfo);

        $img = new SimpleImage($orgFilePath);
        $this->checkImageDimensions($img);
        $img->thumbnail(200, 200)->save($newFilePath, null, $fileInfo->getExtension());

        $this->profilePic = $newFileName;
        copy($orgFilePath, $newFilePath);

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

    private function checkFileExtension(\SplFileInfo $fileInfo)
    {
        if (!in_array(strtolower($fileInfo->getExtension()), [
            'jpg', 'jpeg', 'png'
        ])
        ) {
            $this->errorHandler->addTaggedError('file', __('Only image files are accepted') . ' (received .' . $fileInfo->getExtension() . ')');
            $this->errorHandler->throwIfNotEmpty();
        }
    }

    private function assertFileExistsOnServer($orgFilePath)
    {
        if (!file_exists($orgFilePath))
            throw new NotFound('filePath');
    }

    private function assertAllInfoHaveBeenSent()
    {
        if (!isset($this->data()->fileName))
            throw new NotEnoughData('filePath');
        if (!isset($this->data()->userID))
            throw new NotEnoughData('userID');
    }

    /**
     * @param $img
     * @throws ErrorHandler
     */
    private function checkImageDimensions(SimpleImage $img)
    {
        if ($img->get_height() < 100 OR $img->get_width() < 100) {
            $this->errorHandler->addTaggedError('file', __('Image must be at least 100 x 100 pixels'));
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
    public $fileName;

    /** @var int */
    public $userID;
}