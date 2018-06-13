<?php

namespace Actions\OpenResources;

use Actions\Uploads\MoveUploadedFromTemp;
use DSI\Entity\User;
use DSI\Service\ErrorHandler;
use Models\Resource;

class OpenResourceCreate
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var Resource */
    private $resource;

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
        if (trim($this->title) == '')
            $this->errorHandler->addTaggedError('title', 'Please type resource name');
        if (trim($this->linkText) == '')
            $this->errorHandler->addTaggedError('link_text', 'Please type link text');
        if (trim($this->linkUrl) == '')
            $this->errorHandler->addTaggedError('link_url', 'Please type link url');
        if (trim($this->image) == '')
            $this->errorHandler->addTaggedError('image', 'Please upload an resource image');

        $this->errorHandler->throwIfNotEmpty();
    }

    private function createResource()
    {
        $exec = new MoveUploadedFromTemp();
        $exec->fileName = $this->image;
        $exec->user = $this->executor;
        $exec->exec();

        $this->resource = new Resource();
        $this->resource->{Resource::Title} = $this->title;
        $this->resource->{Resource::Description} = $this->description;
        $this->resource->{Resource::LinkText} = $this->linkText;
        $this->resource->{Resource::LinkUrl} = $this->linkUrl;
        $this->resource->{Resource::Image} = $exec->getNewFileName();
        $this->resource->save();
    }
}