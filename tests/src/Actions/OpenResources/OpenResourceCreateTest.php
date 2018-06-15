<?php

require_once __DIR__ . '/../../../config.php';

use \Models\Resource;
use \Models\Relationship\ResourceCluster;

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
    }

    /** @test */
    public function CanCreateResource()
    {
        $exec = new \Actions\OpenResources\OpenResourceCreate();
        $exec->title = $title = 'Title';
        $exec->description = $description = 'Description';
        $exec->linkUrl = $linkUrl = 'http://';
        $exec->linkText = $linkText = 'Click here';
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
        $this->createClusters(7);

        $exec = new \Actions\OpenResources\OpenResourceCreate();
        $exec->title = $title = 'Title';
        $exec->description = $description = 'Description';
        $exec->linkUrl = $linkUrl = 'http://';
        $exec->linkText = $linkText = 'Click here';
        $exec->clusters = $clusters = [
            1 => 1,
            3 => 1
        ];
        $exec->exec();
        $resource = $exec->getResource();

        foreach ($clusters AS $clusterId => $value) {
            $resourceCluster = ResourceCluster::where([
                ResourceCluster::ResourceID => $resource->getId(),
                ResourceCluster::ClusterID => $clusterId,
            ])->get();
            $this->assertCount(1, $resourceCluster);
        }
    }

    private function createClusters($count = 1)
    {
        for ($i = 0; $i < $count; $i++) {
            $cluster = new \Models\Cluster();
            $cluster->save();
        }
    }
}