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

        if (trim($this->lang) == '')
            $this->errorHandler->addTaggedError('lang', 'Please select a cluster language');

        $this->errorHandler->throwIfNotEmpty();
    }

    private function createClusterLang()
    {
        $clusterLang = new ClusterLang();
        $clusterLang->{ClusterLang::ClusterID} = (int)$this->clusterID;
        $clusterLang->{ClusterLang::Lang} = $this->lang;
        $clusterLang->{ClusterLang::Title} = $this->title;
        $clusterLang->{ClusterLang::Description} = $this->description;
        $clusterLang->save();
    }
}