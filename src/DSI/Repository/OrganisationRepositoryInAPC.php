<?php

namespace DSI\Repository;

use DSI;
use DSI\Entity\Organisation;

class OrganisationRepositoryInAPC extends OrganisationRepository
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

    public function getById(int $id): Organisation
    {
        foreach ($this->getAllFromCache() AS $organisation) {
            if ($id == $organisation->getId())
                return $organisation;
        }

        throw new DSI\NotFound();
    }

    /**
     * @return Organisation[]
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