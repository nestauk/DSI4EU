<?php

namespace DSI\Repository;

use DSI;
use DSI\Entity\Project;

class ProjectRepositoryInAPC extends ProjectRepository
{
    private static $apcKey = null;

    public static function setApcKey($apcKey)
    {
        self::$apcKey = $apcKey;
    }

    public static function getApcKey()
    {
        return self::$apcKey;
    }

    public function getById(int $id): Project
    {
        foreach ($this->getAllFromCache() AS $project) {
            if ($id == $project->getId())
                return $project;
        }

        throw new DSI\NotFound();
    }

    /**
     * @return Project[]
     */
    public function getAll()
    {
        return $this->getAllFromCache();
    }

    public function clearAll()
    {
        parent::clearAll();
        self::resetCache();
    }

    /**
     * @return int
     */
    public function countAll()
    {
        return count($this->getAllFromCache());
    }

    public static function resetCache()
    {
        \apcu_delete(self::$apcKey);
    }

    private function getAllFromCache()
    {
        if (!\apcu_exists(self::$apcKey)) {
            error_log('NOT apcu_exists');
            $results = parent::getAll();
            \apcu_store(self::$apcKey, $results);
            return $results;
        }

        error_log('apcu_exists');
        $results = \apcu_fetch(self::$apcKey, $success);
        if (!$success) {
            error_log(self::$apcKey . ' NOT found');
            return parent::getAll();
        }
        error_log(self::$apcKey . ' found');

        return $results;
    }
}