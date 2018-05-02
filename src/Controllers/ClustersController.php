<?php

namespace Controllers;

use DSI\Entity\Translation;
use DSI\Entity\User;
use DSI\Service\Auth;
use DSI\Service\Translate;
use Models\Cluster;
use Services\URL;
use Models\ClusterLang;
use Services\View;

class ClustersController
{
    /** @var URL */
    private $urlHandler;

    /** @var Auth */
    private $authUser;

    /** @var User */
    private $loggedInUser;

    public function exec()
    {
        $this->urlHandler = new URL();
        $this->authUser = new Auth();
        $this->loggedInUser = $this->authUser->getUserIfLoggedIn();

        $clusters = $this->getClusters();

        $refetch = false;
        foreach ($clusters AS $cluster) {
            if ($cluster->clusterLangs->count() === 0) {
                $clusterLang = new ClusterLang();
                $clusterLang->{ClusterLang::ClusterID} = $cluster->getId();
                $clusterLang->{ClusterLang::Lang} = Translate::getCurrentLang();
                $clusterLang->{ClusterLang::Title} = 'Cluster Title';
                $clusterLang->{ClusterLang::Description} = 'Cluster Description';
                $clusterLang->save();

                $refetch = true;
            }
        }

        if ($refetch)
            $clusters = $this->getClusters();

        View::render(__DIR__ . '/../Views/clusters/clusters.php', [
            'urlHandler' => $this->urlHandler,
            'loggedInUser' => $this->loggedInUser,
            'clusters' => $clusters->map(function (Cluster $cluster) {
                return $cluster->clusterLangs[0];
            }),
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function getClusters()
    {
        return Cluster
            ::with(['clusterLangs' => function ($query) {
                $query->where(ClusterLang::Lang, Translate::getCurrentLang());
            }])
            ->get();
    }
}