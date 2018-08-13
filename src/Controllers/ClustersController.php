<?php

namespace Controllers;

use Actions\Clusters\ClusterLangCreate;
use DSI\Entity\Translation;
use DSI\Entity\User;
use DSI\Service\Auth;
use DSI\Service\Translate;
use Models\Cluster;
use Services\URL;
use Models\Relationship\ClusterLang;
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
                $exec = new ClusterLangCreate();
                $exec->clusterID = $cluster->getId();
                $exec->lang = Translate::getCurrentLang();
                $exec->title = "Cluster title";
                $exec->description = "Cluster description";
                $exec->exec();

                $refetch = true;
            }
        }

        if ($refetch)
            $clusters = $this->getClusters();

        View::setPageTitle('Clusters - DSI4EU');
        return View::render(__DIR__ . '/../Views/clusters/clusters.php', [
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
            ->where(Cluster::IsOther, false)
            ->get();
    }
}