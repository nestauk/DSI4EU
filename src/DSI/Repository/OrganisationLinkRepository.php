<?php

namespace DSI\Repository;

use DSI\DuplicateEntry;
use DSI\Entity\OrganisationLink;
use DSI\NotFound;
use DSI\Service\SQL;

class OrganisationLinkRepository
{
    /** @var OrganisationRepository */
    private $organisationRepository;

    public function __construct()
    {
        $this->organisationRepository = new OrganisationRepository();
    }

    public function add(OrganisationLink $organisationLink)
    {
        $query = new SQL("SELECT organisationID 
            FROM `organisation-links`
            WHERE `organisationID` = '{$organisationLink->getOrganisationID()}'
            AND `link` = '{$organisationLink->getLink()}'
            LIMIT 1
        ");
        if ($query->fetch('organisationID') > 0)
            throw new DuplicateEntry("organisationID: {$organisationLink->getOrganisationID()} / link: {$organisationLink->getLink()}");

        $insert = array();
        $insert[] = "`organisationID` = '" . (int)($organisationLink->getOrganisationID()) . "'";
        $insert[] = "`link` = '" . ($organisationLink->getLink()) . "'";

        $query = new SQL("INSERT INTO `organisation-links` SET " . implode(', ', $insert) . "");
        $query->query();
    }

    public function remove(OrganisationLink $organisationLink)
    {
        $query = new SQL("SELECT organisationID 
            FROM `organisation-links`
            WHERE `organisationID` = '{$organisationLink->getOrganisationID()}'
            AND `link` = '{$organisationLink->getLink()}'
            LIMIT 1
        ");
        if (!$query->fetch('organisationID'))
            throw new NotFound("organisationID: {$organisationLink->getOrganisationID()} / link: {$organisationLink->getLink()}");

        $insert = array();
        $insert[] = "`organisationID` = '" . (int)($organisationLink->getOrganisationID()) . "'";
        $insert[] = "`link` = '" . ($organisationLink->getLink()) . "'";

        $query = new SQL("DELETE FROM `organisation-links` WHERE " . implode(' AND ', $insert) . "");
        $query->query();
    }

    /**
     * @param int $organisationID
     * @return \DSI\Entity\OrganisationLink[]
     */
    public function getByOrganisationID(int $organisationID)
    {
        return $this->getOrganisationLinksWhere([
            "`organisationID` = '{$organisationID}'"
        ]);
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `organisation-links`");
        $query->query();
    }

    /**
     * @param $where
     * @return \DSI\Entity\OrganisationLink[]
     */
    private function getOrganisationLinksWhere($where)
    {
        /** @var OrganisationLink[] $organisationLinks */
        $organisationLinks = [];
        $query = new SQL("SELECT organisationID, link 
            FROM `organisation-links`
            WHERE " . implode(' AND ', $where) . "
        ");
        foreach ($query->fetch_all() AS $dbOrganisationLink) {
            $organisationLink = new OrganisationLink();
            $organisationLink->setOrganisation($this->organisationRepository->getById($dbOrganisationLink['organisationID']));
            $organisationLink->setLink($dbOrganisationLink['link']);
            $organisationLinks[] = $organisationLink;
        }

        return $organisationLinks;
    }

    /**
     * @param int $organisationID
     * @return string[]
     */
    public function getLinksByOrganisationID(int $organisationID)
    {
        $query = new SQL("SELECT link 
            FROM `organisation-links` 
            WHERE `organisationID` = '{$organisationID}'
            ORDER BY `link`
        ");
        return $query->fetch_all('link');
    }

    /**
     * @param int $organisationID
     * @param string $link
     * @return bool
     */
    public function organisationHasLink($organisationID, $link){
        return in_array($link, $this->getLinksByOrganisationID($organisationID));
    }
}