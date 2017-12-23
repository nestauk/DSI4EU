<?php

namespace DSI\Repository;

use DSI;
use DSI\Entity\Project;

class ProjectRepoInAPC extends ProjectRepo
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
        if (function_exists("apcu_exists"))
            \apcu_delete(self::$apcKey);
    }

    private function getAllFromCache()
    {
        if (!function_exists("apcu_exists"))
            return parent::getAll();

        if (!\apcu_exists(self::$apcKey)) {
            $results = parent::getAll();
            \apcu_store(self::$apcKey, $results);
            return $results;
        }

        $results = \apcu_fetch(self::$apcKey, $success);
        if (!$success) {
            error_log('could not fetch ' . self::$apcKey . ' value');
            return parent::getAll();
        }

        return $results;
    }
}