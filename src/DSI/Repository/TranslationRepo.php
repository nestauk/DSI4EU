<?php

namespace DSI\Repository;

use DSI;
use DSI\Entity\Translation;
use DSI\Service\SQL;

class TranslationRepo
{
    public function insert(Translation $translation)
    {
        $query = new SQL("SELECT `index` FROM `translate` WHERE `index` = '".addslashes($translation->getIndex())."' LIMIT 1");
        $existingObject = $query->fetch();
        if ($existingObject)
            throw new DSI\DuplicateEntry('translation index: ' . $translation->getIndex());

        $insert = array();
        $insert[] = "`index` = '" . addslashes($translation->getIndex()) . "'";
        $insert[] = "`details` = '" . addslashes($translation->getDetails()) . "'";
        foreach (Translation::LANGUAGES AS $lang)
            $insert[] = "`" . addslashes($lang) . "` = '" . addslashes($translation->getTranslationOrEmptyFor($lang)) . "'";

        $query = new SQL("INSERT INTO `translate` SET " . implode(', ', $insert));
        $query->query();
    }

    public function save(Translation $translation)
    {
        $query = new SQL("SELECT `index` FROM `translate` WHERE `index` = '".addslashes($translation->getIndex())."' LIMIT 1");
        $existingObject = $query->fetch();
        if (!$existingObject)
            throw new DSI\NotFound('translation index: ' . $translation->getIndex());

        $insert = array();
        $insert[] = "`details` = '" . addslashes($translation->getDetails()) . "'";
        foreach (Translation::LANGUAGES AS $lang)
            $insert[] = "`" . addslashes($lang) . "` = '" . addslashes($translation->getTranslationOrEmptyFor($lang)) . "'";

        $query = new SQL("UPDATE `translate` SET " . implode(', ', $insert) . " WHERE `index` = '{$translation->getIndex()}'");
        $query->query();
    }

    /**
     * @param $translationData
     * @return Translation
     */
    private function buildUserFromData($translationData)
    {
        $translation = new Translation();
        $translation->setIndex($translationData['index']);
        $translation->setDetails($translationData['details']);
        foreach (Translation::LANGUAGES AS $lang)
            $translation->setTranslationFor($lang, $translationData[$lang]);

        return $translation;
    }

    /**
     * @param int $index
     * @return Translation
     */
    public function getByIndex($index)
    {
        return $this->getObjectWhere([
            "`index` = '" . addslashes($index) . "'"
        ]);
    }

    /** @return Translation[] */
    public function getAll()
    {
        return $this->getObjectsWhere(["1"]);
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `translate`");
        $query->query();
    }

    /**
     * @param $where
     * @return Translation
     * @throws DSI\NotFound
     */
    private function getObjectWhere($where)
    {
        $objects = $this->getObjectsWhere($where);
        if (count($objects) < 1)
            throw new DSI\NotFound();

        return $objects[0];
    }

    /**
     * @param $where
     * @return Translation[]
     */
    private function getObjectsWhere($where)
    {
        $objects = [];
        $fields = ['`index`', '`details`'];
        foreach (Translation::LANGUAGES AS $lang)
            $fields[] = "`" . addslashes($lang) . "`";

        $query = new SQL("SELECT " . implode(', ', $fields) . " FROM `translate` WHERE " . implode(' AND ', $where) . "");
        foreach ($query->fetch_all() AS $dnObject) {
            $objects[] = $this->buildUserFromData($dnObject);
        }
        return $objects;
    }
}