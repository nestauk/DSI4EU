<?php

namespace Actions\Clusters;

use DSI\Service\ErrorHandler;
use Models\ClusterLang;

class ClusterLangCreate
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ClusterLang */
    private $clusterLang;

    public $clusterID,
        $lang,
        $title,
        $description;

    public function __construct()
    {
        $this->errorHandler = new ErrorHandler();
    }

    public function exec()
    {
        $this->assertDataHasBeenSent();
        $this->createClusterLang();
    }

    /**
     * @return ClusterLang
     */
    public function getClusterLang()
    {
        return $this->clusterLang;
    }

    private function assertDataHasBeenSent()
    {
        if ((int)$this->clusterID === 0)
            $this->errorHandler->addTaggedError('clusterID', 'Please select a cluster');

        if (trim($this->title) == '')
            $this->errorHandler->addTaggedError('title', 'Please type cluster name');

        $this->errorHandler->throwIfNotEmpty();
    }

    private function createClusterLang()
    {
        if (!$this->lang)
            $this->lang = 'en';

        $this->clusterLang = new ClusterLang();
        $this->clusterLang->{ClusterLang::ClusterID} = (int)$this->clusterID;
        $this->clusterLang->{ClusterLang::Lang} = $this->lang;
        $this->clusterLang->{ClusterLang::Title} = $this->title;
        $this->clusterLang->{ClusterLang::Description} = $this->description;
        $this->clusterLang->save();
    }
}