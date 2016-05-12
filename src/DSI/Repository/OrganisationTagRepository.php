<?php

namespace DSI\Repository;

use DSI\DuplicateEntry;
use DSI\Entity\OrganisationTag;
use DSI\NotFound;
use DSI\Service\SQL;

class OrganisationTagRepository
{
    /** @var OrganisationRepository */
    private $organisationRepo;

    /** @var TagForOrganisationsRepository */
    private $tagsRepo;

    public function __construct()
    {
        $this->organisationRepo = new OrganisationRepository();
        $this->tagsRepo = new TagForOrganisationsRepository();
    }

    public function add(OrganisationTag $organisationTag)
    {
        $query = new SQL("SELECT organisationID 
            FROM `organisation-tags`
            WHERE `organisationID` = '{$organisationTag->getOrganisationID()}'
            AND `tagID` = '{$organisationTag->getTagID()}'
            LIMIT 1
        ");
        if ($query->fetch('organisationID') > 0)
            throw new DuplicateEntry("organisationID: {$organisationTag->getOrganisationID()} / tagID: {$organisationTag->getTagID()}");

        $insert = array();
        $insert[] = "`organisationID` = '" . (int)($organisationTag->getOrganisationID()) . "'";
        $insert[] = "`tagID` = '" . (int)($organisationTag->getTagID()) . "'";

        $query = new SQL("INSERT INTO `organisation-tags` SET " . implode(', ', $insert) . "");
        // $query->pr();
        $query->query();
    }

    public function remove(OrganisationTag $organisationTag)
    {
        $query = new SQL("SELECT organisationID 
            FROM `organisation-tags`
            WHERE `organisationID` = '{$organisationTag->getOrganisationID()}'
            AND `tagID` = '{$organisationTag->getTagID()}'
            LIMIT 1
        ");
        if (!$query->fetch('organisationID'))
            throw new NotFound("organisationID: {$organisationTag->getOrganisationID()} / tagID: {$organisationTag->getTagID()}");

        $insert = array();
        $insert[] = "`organisationID` = '" . (int)($organisationTag->getOrganisationID()) . "'";
        $insert[] = "`tagID` = '" . (int)($organisationTag->getTagID()) . "'";

        $query = new SQL("DELETE FROM `organisation-tags` WHERE " . implode(' AND ', $insert) . "");
        $query->query();
    }

    /**
     * @param int $organisationID
     * @return \DSI\Entity\OrganisationTag[]
     */
    public function getByOrganisationID(int $organisationID)
    {
        return $this->getOrganisationTagsWhere([
            "`organisationID` = '{$organisationID}'"
        ]);
    }

    /**
     * @param int $organisationID
     * @return \int[]
     */
    public function getTagIDsForOrganisation(int $organisationID)
    {
        $where = [
            "`organisationID` = '{$organisationID}'"
        ];

        /** @var int[] $tagIDs */
        $tagIDs = [];
        $query = new SQL("SELECT tagID 
            FROM `organisation-tags`
            WHERE " . implode(' AND ', $where) . "
            ORDER BY tagID
        ");
        foreach ($query->fetch_all() AS $dbOrganisationTags) {
            $tagIDs[] = $dbOrganisationTags['tagID'];
        }

        return $tagIDs;
    }

    /**
     * @param int $tagID
     * @return \DSI\Entity\OrganisationTag[]
     */
    public function getByTagID(int $tagID)
    {
        return $this->getOrganisationTagsWhere([
            "`tagID` = '{$tagID}'"
        ]);
    }

    /**
     * @param int $tagID
     * @return \int[]
     */
    public function getOrganisationIDsForTag(int $tagID)
    {
        $where = [
            "`tagID` = '{$tagID}'"
        ];

        /** @var int[] $organisationIDs */
        $organisationIDs = [];
        $query = new SQL("SELECT organisationID 
            FROM `organisation-tags`
            WHERE " . implode(' AND ', $where) . "
            ORDER BY organisationID
        ");
        foreach ($query->fetch_all() AS $dbOrganisationTag) {
            $organisationIDs[] = $dbOrganisationTag['organisationID'];
        }

        return $organisationIDs;
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `organisation-tags`");
        $query->query();
    }

    /**
     * @param $where
     * @return \DSI\Entity\OrganisationTag[]
     */
    private function getOrganisationTagsWhere($where)
    {
        /** @var OrganisationTag[] $organisationTags */
        $organisationTags = [];
        $query = new SQL("SELECT organisationID, tagID 
            FROM `organisation-tags`
            WHERE " . implode(' AND ', $where) . "
        ");
        foreach ($query->fetch_all() AS $dbOrganisationTag) {
            $organisationTag = new OrganisationTag();
            $organisationTag->setOrganisation($this->organisationRepo->getById($dbOrganisationTag['organisationID']));
            $organisationTag->setTag($this->tagsRepo->getById($dbOrganisationTag['tagID']));
            $organisationTags[] = $organisationTag;
        }

        return $organisationTags;
    }

    public function organisationHasTagName(int $organisationID, string $tagName)
    {
        $organisationTags = $this->getByOrganisationID($organisationID);
        foreach ($organisationTags AS $organisationTag) {
            if ($tagName == $organisationTag->getTag()->getName()) {
                return true;
            }
        }

        return false;
    }

    public function getTagsNameByOrganisationID(int $organisationID)
    {
        $query = new SQL("SELECT tag 
            FROM `tags-for-organisations` 
            LEFT JOIN `organisation-tags` ON `tags-for-organisations`.`id` = `organisation-tags`.`tagID`
            WHERE `organisation-tags`.`organisationID` = '{$organisationID}'
            ORDER BY `tags-for-organisations`.`tag`
        ");
        return $query->fetch_all('tag');
    }
}