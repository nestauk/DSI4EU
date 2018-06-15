<?php

require_once __DIR__ . '/../../../config.php';

use \Models\Resource;
use \Models\Relationship\ResourceCluster;

class OpenResourceEditTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    public function tearDown()
    {
        Resource::truncate();
        ResourceCluster::truncate();
        \Models\Cluster::truncate();
        \Models\AuthorOfResource::truncate();
    }

    /** @test */
    public function CanEditResource()
    {
        $authors = $this->createAuthors();

        $resource = $this->createResource();
        $exec = new \Actions\OpenResources\OpenResourceEdit();
        $exec->resource = $resource;
        $exec->title = $title = 'Title';
        $exec->description = $description = 'Description';
        $exec->linkUrl = $linkUrl = 'http://';
        $exec->linkText = $linkText = 'Click here';
        $exec->authorID = $author = $authors[0]->getId();
        $exec->exec();

        /** @var Resource $resource */
        $resource = Resource::first();

        $this->assertEquals($title, $resource->{Resource::Title});
        $this->assertEquals($description, $resource->{Resource::Description});
        $this->assertEquals($linkUrl, $resource->{Resource::LinkUrl});
        $this->assertEquals($linkText, $resource->{Resource::LinkText});
    }

    /** @test */
    public function CanSaveResourceClusters()
    {
        $authors = $this->createAuthors();
        $this->createClusters(7);

        $resource = $this->createResource();
        $exec = new \Actions\OpenResources\OpenResourceEdit();
        $exec->resource = $resource;
        $exec->title = $title = 'Title';
        $exec->description = $description = 'Description';
        $exec->linkUrl = $linkUrl = 'http://';
        $exec->linkText = $linkText = 'Click here';
        $exec->authorID = $author = $authors[0]->getId();
        $exec->clusters = $clusters = [
            1 => 1,
            3 => 1
        ];
        $exec->exec();

        foreach ($clusters AS $clusterId => $value) {
            $resourceCluster = ResourceCluster::where([
                ResourceCluster::ResourceID => $resource->getId(),
                ResourceCluster::ClusterID => $clusterId,
            ])->get();
            $this->assertCount(1, $resourceCluster);
        }
    }

    /** @test */
    public function CanSaveResourceAuthor()
    {
        $authors = $this->createAuthors();
        $this->createClusters(7);

        $resource = $this->createResource();
        $exec = new \Actions\OpenResources\OpenResourceEdit();
        $exec->resource = $resource;
        $exec->title = $title = 'Title';
        $exec->description = $description = 'Description';
        $exec->linkUrl = $linkUrl = 'http://';
        $exec->linkText = $linkText = 'Click here';
        $exec->authorID = $author = $authors[0]->getId();
        $exec->exec();

        $this->assertEquals($authors[0]->getId(), $resource->{Resource::AuthorID});
    }

    /** @return Resource */
    private function createResource(): Resource
    {
        $resource = new Resource();
        $resource->save();
        return $resource;
    }

    private function createClusters($count = 1)
    {
        for ($i = 0; $i < $count; $i++) {
            $cluster = new \Models\Cluster();
            $cluster->save();
        }
    }

    private function createAuthors()
    {
        $authors = [];

        $authors[0] = new \Models\AuthorOfResource();
        $authors[0]->{\Models\AuthorOfResource::Name} = 'DSI4EU';
        $authors[0]->save();

        $authors[1] = new \Models\AuthorOfResource();
        $authors[1]->{\Models\AuthorOfResource::Name} = 'Other';
        $authors[1]->save();

        return $authors;
    }
}