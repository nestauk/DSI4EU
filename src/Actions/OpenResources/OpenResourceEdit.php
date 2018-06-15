<?php

namespace Actions\OpenResources;

use Actions\Uploads\MoveUploadedFromTemp;
use DSI\Entity\User;
use DSI\NotEnoughData;
use DSI\Service\ErrorHandler;
use Models\Relationship\ResourceCluster;
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

    /** @var int[] */
    public $clusters;

    public function __construct()
    {
        $this->errorHandler = new ErrorHandler();
    }

    public function exec()
    {
        $this->assertDataHasBeenSent();
        $this->saveResource();
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

    private function saveResource()
    {
        $this->saveImage($this->resource);

        $this->resource->{Resource::Title} = $this->title;
        $this->resource->{Resource::Description} = $this->description;
        $this->resource->{Resource::LinkText} = $this->linkText;
        $this->resource->{Resource::LinkUrl} = $this->linkUrl;
        $this->resource->save();

        $this->saveClusters($this->resource);
    }

    private function saveImage(Resource $resource)
    {
        if ($this->image) {
            $exec = new MoveUploadedFromTemp();
            $exec->fileName = $this->image;
            $exec->user = $this->executor;
            $exec->exec();

            $resource->{Resource::Image} = $exec->getNewFileName();
        }
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