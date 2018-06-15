<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository\UserRepo;

use \Models\Relationship\ClusterLang;

class ClusterLangCreateTest extends PHPUnit_Framework_TestCase
{
    /** @var UserRepo */
    private $userRepository;

    public function setUp()
    {
        $this->userRepository = new UserRepo();
    }

    public function tearDown()
    {
        $this->userRepository->clearAll();
        \Models\Cluster::truncate();
        ClusterLang::truncate();
    }

    /** @test */
    public function CanCreateClusterLang()
    {
        $loggedInUser = $this->createUser();
        $exec = new \Actions\Clusters\ClusterLangCreate();
        $exec->clusterID = $this->createCluster()->getId();
        $exec->title = 'Title';
        $exec->description = 'Description';
        $exec->lang = 'en';
        $exec->exec();

        $clusterLang = $exec->getClusterLang();
        $this->assertGreaterThan(0, $clusterLang->getId());
    }

    /** @test */
    public function CannotCreateClusterLangWithoutClusterId()
    {
        $loggedInUser = $this->createUser();
        $exec = new \Actions\Clusters\ClusterLangCreate();
        // $exec->clusterID = $this->createCluster()->getId();
        $exec->title = 'Title';
        $exec->description = 'Description';
        $exec->lang = 'en';
        try {
            $exec->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
            $this->assertNotEmpty($e->getTaggedError('clusterID'));
        }
    }

    /** @test */
    public function CannotCreateClusterLangWithoutTitle()
    {
        $loggedInUser = $this->createUser();
        $exec = new \Actions\Clusters\ClusterLangCreate();
        $exec->clusterID = $this->createCluster()->getId();
        // $exec->title = 'Title';
        $exec->description = 'Description';
        $exec->lang = 'en';
        try {
            $exec->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
            $this->assertNotEmpty($e->getTaggedError('title'));
        }
    }

    /** @test */
    public function CreateClusterLangDefaultsToEnglish()
    {
        $loggedInUser = $this->createUser();
        $exec = new \Actions\Clusters\ClusterLangCreate();
        $exec->clusterID = $this->createCluster()->getId();
        $exec->title = 'Title';
        $exec->description = 'Description';
        // $exec->lang = 'en';
        $exec->exec();

        $clusterLang = $exec->getClusterLang();
        $this->assertEquals('en', $clusterLang->getLang());
    }

    /**
     * @return \DSI\Entity\User
     */
    private function createUser(): \DSI\Entity\User
    {
        $user = new \DSI\Entity\User();
        $this->userRepository->insert($user);
        return $user;
    }

    /**
     * @return \Models\Cluster
     */
    private function createCluster(): \Models\Cluster
    {
        $cluster = new \Models\Cluster();
        $cluster->save();
        return $cluster;
    }
}