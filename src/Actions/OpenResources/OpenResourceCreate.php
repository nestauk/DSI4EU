<?php

namespace Actions\OpenResources;

use Actions\Uploads\MoveUploadedFromTemp;
use DSI\Entity\User;
use DSI\Service\ErrorHandler;
use Models\Relationship\ResourceCluster;
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

    /** @var int */
    public $authorID;

    /** @var int[] */
    public $clusters;

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
            $this->errorHandler->addTaggedError(Resource::Title, 'Please type resource name');
        if (trim($this->linkText) == '')
            $this->errorHandler->addTaggedError(Resource::LinkText, 'Please type link text');
        if (trim($this->linkUrl) == '')
            $this->errorHandler->addTaggedError(Resource::LinkUrl, 'Please type link url');
        if (!$this->authorID)
            $this->errorHandler->addTaggedError(Resource::AuthorID, 'Please select an author');
        if ($this->image AND trim($this->image) == '')
            $this->errorHandler->addTaggedError(Resource::Image, 'Please upload an resource image');

        $this->errorHandler->throwIfNotEmpty();
    }

    private function createResource()
    {
        $this->resource = new Resource();
        $this->resource->{Resource::Title} = $this->title;
        $this->resource->{Resource::Description} = $this->description;
        $this->resource->{Resource::LinkText} = $this->linkText;
        $this->resource->{Resource::LinkUrl} = $this->linkUrl;
        $this->resource->{Resource::AuthorID} = $this->authorID;

        if ($this->image) {
            $exec = new MoveUploadedFromTemp();
            $exec->fileName = $this->image;
            $exec->user = $this->executor;
            $exec->exec();
            $this->resource->{Resource::Image} = $exec->getNewFileName();
        }

        $this->resource->save();
        $this->saveClusters($this->resource);
    }

    private function saveClusters(Resource $resource)
    {
        ResourceCluster
            ::where(ResourceCluster::ResourceID, $resource->getId())
            ->delete();

        foreach ((array)$this->clusters AS $clusterID => $value) {
            if ($value == "1") {
                $resourceCluster = new ResourceCluster();
                $resourceCluster->{ResourceCluster::ResourceID} = $resource->getId();
                $resourceCluster->{ResourceCluster::ClusterID} = $clusterID;
                $resourceCluster->save();
            }
        }
    }
}