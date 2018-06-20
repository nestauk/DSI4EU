<?php

namespace Actions\Uploads;

use DSI\Entity\Image;
use DSI\NotEnoughData;
use DSI\NotFound;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;
use Models\Upload;

class UploadFile
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UserRepo */
    private $userRepo;

    /** @var string */
    private $imagePath;

    /** @var string */
    public $filePath,
        $fileName,
        $format;

    public function __construct()
    {
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->userRepo = new UserRepo();

        $this->checkIfAllTheInfoHaveBeenSent();
        $this->checkIfFileExistsOnServer();
        $this->checkFilename();

        $upload = new Upload();
        $upload->{Upload::Filename} = $this->fileName;
        $upload->save();

        $this->imagePath = $upload->getId() . '-' . $this->fileName;
        rename($this->filePath, Image::UPLOAD_FOLDER . $this->imagePath);
    }

    /**
     * @return string
     */
    public function getImagePath()
    {
        return $this->imagePath;
    }

    private function checkFilename()
    {
        if (basename($this->fileName) != $this->fileName) {
            $this->errorHandler->addTaggedError('file', 'Invalid file name. Try renaming the file');
            $this->errorHandler->throwIfNotEmpty();
        }
    }

    private function checkIfFileExistsOnServer()
    {
        if (!file_exists($this->filePath))
            throw new ErrorHandler('filePath');
    }

    private function checkIfAllTheInfoHaveBeenSent()
    {
        if (!isset($this->filePath))
            throw new NotEnoughData('filePath');
        if (!isset($this->fileName))
            throw new NotEnoughData('fileName');
    }
}