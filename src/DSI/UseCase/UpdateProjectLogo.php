<?php

namespace DSI\UseCase;

use abeautifulsite\SimpleImage;
use DSI\Entity\Image;
use DSI\NotEnoughData;
use DSI\NotFound;
use DSI\Repository\ProjectRepository;
use DSI\Service\ErrorHandler;

class UpdateProjectLogo
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UpdateProjectLogo_Data */
    private $data;

    /** @var ProjectRepository */
    private $projectRepository;

    /** @var string */
    private $logo;

    public function __construct()
    {
        $this->data = new UpdateProjectLogo_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectRepository = new ProjectRepository();

        $this->assertAllInfoHaveBeenSent();

        $orgFileName = basename($this->data()->fileName);
        $orgFilePath = Image::TEMP_FOLDER . $orgFileName;
        $newFileName = $this->data()->projectID . '-' . $orgFileName;
        $newFilePath = Image::PROJECT_LOGO . $newFileName;

        $this->assertFileExistsOnServer($orgFilePath);

        $fileInfo = new \SplFileInfo($orgFilePath);
        $this->checkFileExtension($fileInfo);

        $img = new SimpleImage($orgFilePath);
        $this->checkImageDimensions($img);
        $img->thumbnail(200, 200)->save($newFilePath, null, $fileInfo->getExtension());

        $this->logo = $newFileName;
        rename($orgFilePath, $newFilePath);

        $this->updateProjectDetails();
    }

    /**
     * @return UpdateProjectLogo_Data
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    private function checkFileExtension(\SplFileInfo $fileInfo)
    {
        if (!in_array(strtolower($fileInfo->getExtension()), [
            'jpg', 'jpeg', 'png'
        ])
        ) {
            $this->errorHandler->addTaggedError('file', 'Only image files accepted (received .' . $fileInfo->getExtension() . ')');
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
        if (!isset($this->data()->projectID))
            throw new NotEnoughData('projectID');
    }

    /**
     * @param $img
     * @throws ErrorHandler
     */
    private function checkImageDimensions(SimpleImage $img)
    {
        if ($img->get_height() < 100 OR $img->get_width() < 100) {
            $this->errorHandler->addTaggedError('file', 'Image must be at least 100x100');
            $this->errorHandler->throwIfNotEmpty();
        }
    }

    private function updateProjectDetails()
    {
        $project = $this->projectRepository->getById($this->data()->projectID);
        $project->setLogo($this->logo);
        $this->projectRepository->save($project);
    }
}

class UpdateProjectLogo_Data
{
    /** @var string */
    public $fileName;

    /** @var int */
    public $projectID;
}