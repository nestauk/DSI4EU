<?php

namespace DSI\Repository;

use DSI;
use DSI\Service\SQL;
use DSI\Entity\Language;

class LanguageRepo
{
    public function saveAsNew(Language $language)
    {
        if (!$language->getName())
            throw new DSI\NotEnoughData('name');
        if ($this->nameExists($language->getName()))
            throw new DSI\DuplicateEntry('name');

        $insert = array();
        $insert[] = "`language` = '" . addslashes($language->getName()) . "'";

        $query = new SQL("INSERT INTO `languages` SET " . implode(', ', $insert) . "");
        $query->query();

        $language->setId($query->insert_id());
    }

    public function save(Language $language)
    {
        if (!$language->getName())
            throw new DSI\NotEnoughData('name');
        if ($this->nameExists($language->getName()))
            if ($this->getByName($language->getName())->getId() != $language->getId())
                throw new DSI\DuplicateEntry('name');

        $query = new SQL("SELECT id FROM `languages` WHERE id = '{$language->getId()}' LIMIT 1");
        $existingLanguage = $query->fetch();
        if (!$existingLanguage)
            throw new DSI\NotFound('langID: ' . $language->getId());

        $insert = array();
        $insert[] = "`language` = '" . addslashes($language->getName()) . "'";

        $query = new SQL("UPDATE `languages` SET " . implode(', ', $insert) . " WHERE `id` = '{$language->getId()}'");
        $query->query();
    }

    public function getById(int $id): Language
    {
        return $this->getLanguageWhere([
            "`id` = {$id}"
        ]);
    }

    public function nameExists(string $name): bool
    {
        return $this->checkExistingLanguageWhere([
            "`language` = '" . addslashes($name) . "'"
        ]);
    }

    public function getByName(string $name): Language
    {
        return $this->getLanguageWhere([
            "`language` = '" . addslashes($name) . "'"
        ]);
    }

    /** @return Language[] */
    public function getAll()
    {
        $where = ["1"];
        $languages = [];
        $query = new SQL("SELECT 
            id, language
          FROM `languages` WHERE " . implode(' AND ', $where) . "");
        foreach ($query->fetch_all() AS $dbLanguage) {
            $languages[] = $this->buildLanguageFromData($dbLanguage);
        }
        return $languages;
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `languages`");
        $query->query();
    }


    /**
     * @param $language
     * @return Language
     */
    private function buildLanguageFromData($language)
    {
        $languageObj = new Language();
        $languageObj->setId($language['id']);
        $languageObj->setName($language['language']);
        return $languageObj;
    }

    /**
     * @param $where
     * @return Language
     * @throws DSI\NotFound
     */
    private function getLanguageWhere($where)
    {
        $query = new SQL("SELECT 
              id, language 
            FROM `languages` WHERE " . implode(' AND ', $where) . " LIMIT 1");
        $dbLanguage = $query->fetch();
        if (!$dbLanguage) {
            throw new DSI\NotFound();
        }

        return $this->buildLanguageFromData($dbLanguage);
    }

    /**
     * @param $where
     * @return bool
     */
    private function checkExistingLanguageWhere($where)
    {
        $query = new SQL("SELECT id FROM `languages` WHERE " . implode(' AND ', $where) . " LIMIT 1");
        return ($query->fetch() ? true : false);
    }
}