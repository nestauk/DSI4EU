<?php

namespace Actions\Uploads;

use abeautifulsite\SimpleImage;
use DSI\Entity\Image;
use DSI\Entity\User;
use DSI\NotEnoughData;
use DSI\NotFound;
use DSI\Service\ErrorHandler;
use Models\Upload;

class MoveUploadedFromTemp
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var string */
    public $fileName;

    /** @var User */
    public $user;

    /** @var string */
    private $newFileName;

    public function __construct()
    {
        $this->errorHandler = new ErrorHandler();
    }

    public function exec()
    {
        $this->assertAllInfoHaveBeenSent();

        $orgFileName = basename($this->fileName);
        $orgFilePath = Image::TEMP_FOLDER . $orgFileName;

        $this->assertFileExistsOnServer($orgFilePath);

        $fileInfo = new \SplFileInfo($orgFilePath);
        $this->checkFileExtension($fileInfo);

        $upload = new Upload();
        $upload->{Upload::Filename} = $orgFileName;
        $upload->save();

        $this->newFileName = $upload->getId() . '-' . $orgFileName;
        $newFilePath = Image::UPLOAD_FOLDER . $this->newFileName;

        $img = new SimpleImage($orgFilePath);
        $img->save($newFilePath, null, $fileInfo->getExtension());
    }

    public function getNewFileName(): string
    {
        return $this->newFileName;
    }

    private function assertAllInfoHaveBeenSent()
    {
        if (!isset($this->fileName))
            throw new NotEnoughData('fileName');
        if (!isset($this->user))
            throw new NotEnoughData('user');
    }

    private function assertFileExistsOnServer($orgFilePath)
    {
        if (!file_exists($orgFilePath))
            throw new NotFound('filePath');
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
}