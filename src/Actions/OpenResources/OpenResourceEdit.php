<?php

namespace Actions\OpenResources;

use Actions\Uploads\MoveUploadedFromTemp;
use DSI\Entity\User;
use DSI\NotEnoughData;
use DSI\Service\ErrorHandler;
use Models\Resource;

class OpenResourceEdit
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var Resource */
    public $resource;

    /** @var User */
    public $executor;

    /** @var string */
    public $title,
        $description,
        $linkText,
        $linkUrl,
        $image;

    public function __construct()
    {
        $this->errorHandler = new ErrorHandler();
    }

    public function exec()
    {
        $this->assertDataHasBeenSent();
        $this->createResource();
    }

    /**
     * @return Resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    private function assertDataHasBeenSent()
    {
        if (!$this->resource)
            throw new NotEnoughData('resource');

        if (trim($this->title) == '')
            $this->errorHandler->addTaggedError('title', 'Please type resource name');
        if (trim($this->linkText) == '')
            $this->errorHandler->addTaggedError('title', 'Please type link text');
        if (trim($this->linkUrl) == '')
            $this->errorHandler->addTaggedError('title', 'Please type link url');

        $this->errorHandler->throwIfNotEmpty();
    }

    private function createResource()
    {
        if ($this->image) {
            $exec = new MoveUploadedFromTemp();
            $exec->fileName = $this->image;
            $exec->user = $this->executor;
            $exec->exec();

            $this->resource->{Resource::Image} = $exec->getNewFileName();
        }

        $this->resource->{Resource::Title} = $this->title;
        $this->resource->{Resource::Description} = $this->description;
        $this->resource->{Resource::LinkText} = $this->linkText;
        $this->resource->{Resource::LinkUrl} = $this->linkUrl;
        $this->resource->save();
    }
}