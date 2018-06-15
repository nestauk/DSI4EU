<?php

require_once __DIR__ . '/../../../config.php';

use \Models\Resource;
use \Models\Relationship\ResourceCluster;
use \Models\Relationship\ResourceType;

class OpenResourceCreateTest extends PHPUnit_Framework_TestCase
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
        \Models\TypeOfResource::truncate();
        ResourceType::truncate();
    }

    /** @test */
    public function CanCreateResource()
    {
        $authors = $this->createAuthors();

        $exec = new \Actions\OpenResources\OpenResourceCreate();
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

        $exec = new \Actions\OpenResources\OpenResourceCreate();
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

        /** @var Resource $resource */
        $resource = Resource::first();

        foreach ($clusters AS $clusterId => $value) {
            $resourceCluster = ResourceCluster::where([
                ResourceCluster::ResourceID => $resource->getId(),
                ResourceCluster::ClusterID => $clusterId,
            ])->get();
            $this->assertCount(1, $resourceCluster);
        }
    }

    /** @test */
    public function CanSaveResourceTypes()
    {
        $authors = $this->createAuthors();
        $this->createTypes(4);

        $exec = new \Actions\OpenResources\OpenResourceCreate();
        $exec->title = $title = 'Title';
        $exec->description = $description = 'Description';
        $exec->linkUrl = $linkUrl = 'http://';
        $exec->linkText = $linkText = 'Click here';
        $exec->authorID = $author = $authors[0]->getId();
        $exec->types = $types = [
            1 => 1,
            3 => 1
        ];
        $exec->exec();

        /** @var Resource $resource */
        $resource = Resource::first();

        foreach ($types AS $typeID => $value) {
            $resourceType = ResourceType::where([
                ResourceType::ResourceID => $resource->getId(),
                ResourceType::TypeID => $typeID,
            ])->get();
            $this->assertCount(1, $resourceType);
        }
    }

    /** @test */
    public function CanSaveResourceAuthor()
    {
        $authors = $this->createAuthors();
        $this->createClusters(7);

        $exec = new \Actions\OpenResources\OpenResourceCreate();
        $exec->title = $title = 'Title';
        $exec->description = $description = 'Description';
        $exec->linkUrl = $linkUrl = 'http://';
        $exec->linkText = $linkText = 'Click here';
        $exec->authorID = $authorID = $authors[0]->getId();
        $exec->exec();

        /** @var Resource $resource */
        $resource = Resource::first();
        $this->assertEquals($authors[0]->getId(), $resource->{Resource::AuthorID});
    }

    private function createClusters($count = 1)
    {
        for ($i = 0; $i < $count; $i++) {
            $cluster = new \Models\Cluster();
            $cluster->save();
        }
    }

    private function createTypes($count = 1)
    {
        for ($i = 0; $i < $count; $i++) {
            $type = new \Models\TypeOfResource();
            $type->save();
        }
    }

    /**
     * @return \Models\AuthorOfResource[]
     */
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