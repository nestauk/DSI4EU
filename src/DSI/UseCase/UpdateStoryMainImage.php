<?php

namespace DSI\UseCase;

use abeautifulsite\SimpleImage;
use DSI\Entity\Image;
use DSI\Entity\Story;
use DSI\NotEnoughData;
use DSI\NotFound;
use DSI\Repository\StoryRepo;
use DSI\Service\ErrorHandler;

class UpdateStoryMainImage
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UpdateStoryMainImage_Data */
    private $data;

    /** @var StoryRepo */
    private $storyRepository;

    /** @var string */
    private $filename;

    public function __construct()
    {
        $this->data = new UpdateStoryMainImage_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->storyRepository = new StoryRepo();

        $this->assertAllInfoHaveBeenSent();

        $orgFileName = basename($this->data()->fileName);
        $orgFilePath = Image::TEMP_FOLDER . $orgFileName;
        $newFileName = $this->data()->story->getId() . '-' . $orgFileName;
        $newFilePath = Image::STORY_MAIN_IMAGE . $newFileName;

        $this->assertFileExistsOnServer($orgFilePath);

        $fileInfo = new \SplFileInfo($orgFilePath);
        $this->checkFileExtension($fileInfo);

        $img = new SimpleImage($orgFilePath);
        $this->checkImageDimensions($img);
        $img
            // ->thumbnail(200, 200)
            ->save($newFilePath, null, $fileInfo->getExtension());

        $this->filename = $newFileName;
        copy($orgFilePath, $newFilePath);

        $this->updateStoryDetails();
    }

    /**
     * @return UpdateStoryMainImage_Data
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
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
            throw new NotEnoughData('fileName');
        if (!isset($this->data()->story))
            throw new NotEnoughData('story');
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

    private function updateStoryDetails()
    {
        $this->data()->story->setMainImage($this->filename);
        $this->storyRepository->save($this->data()->story);
    }
}

class UpdateStoryMainImage_Data
{
    /** @var string */
    public $fileName;

    /** @var Story */
    public $story;
}