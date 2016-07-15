<?php

namespace DSI\Repository;

use DSI;
use DSI\Entity\CaseStudy;
use DSI\Service\SQL;

class CaseStudyRepository
{
    public function insert(CaseStudy $caseStudy)
    {
        $insert = array();
        $insert[] = "`title` = '" . addslashes($caseStudy->getTitle()) . "'";
        $insert[] = "`introCardText` = '" . addslashes($caseStudy->getIntroCardText()) . "'";
        $insert[] = "`introPageText` = '" . addslashes($caseStudy->getIntroPageText()) . "'";
        $insert[] = "`mainText` = '" . addslashes($caseStudy->getMainText()) . "'";
        $insert[] = "`projectStartDate` = '" . addslashes($caseStudy->getProjectStartDate()) . "'";
        $insert[] = "`projectEndDate` = '" . addslashes($caseStudy->getProjectEndDate()) . "'";
        $insert[] = "`url` = '" . addslashes($caseStudy->getUrl()) . "'";
        $insert[] = "`buttonLabel` = '" . addslashes($caseStudy->getButtonLabel()) . "'";
        $insert[] = "`logo` = '" . addslashes($caseStudy->getLogo()) . "'";
        $insert[] = "`cardImage` = '" . addslashes($caseStudy->getCardImage()) . "'";
        $insert[] = "`headerImage` = '" . addslashes($caseStudy->getHeaderImage()) . "'";
        $insert[] = "`cardColour` = '" . addslashes($caseStudy->getCardColour()) . "'";
        $insert[] = "`isPublished` = '" . addslashes($caseStudy->isPublished()) . "'";
        $insert[] = "`regionID` = '" . addslashes($caseStudy->getRegionID()) . "'";

        $query = new SQL("INSERT INTO `case-studies` SET " . implode(', ', $insert) . "");
        $query->query();

        $caseStudy->setId($query->insert_id());
    }

    public function save(CaseStudy $caseStudy)
    {
        $query = new SQL("SELECT id FROM `case-studies` WHERE id = '{$caseStudy->getId()}' LIMIT 1");
        $existingElement = $query->fetch();
        if (!$existingElement)
            throw new DSI\NotFound('id: ' . $caseStudy->getId());

        $insert = array();
        $insert[] = "`title` = '" . addslashes($caseStudy->getTitle()) . "'";
        $insert[] = "`introCardText` = '" . addslashes($caseStudy->getIntroCardText()) . "'";
        $insert[] = "`introPageText` = '" . addslashes($caseStudy->getIntroPageText()) . "'";
        $insert[] = "`mainText` = '" . addslashes($caseStudy->getMainText()) . "'";
        $insert[] = "`projectStartDate` = '" . addslashes($caseStudy->getProjectStartDate()) . "'";
        $insert[] = "`projectEndDate` = '" . addslashes($caseStudy->getProjectEndDate()) . "'";
        $insert[] = "`url` = '" . addslashes($caseStudy->getUrl()) . "'";
        $insert[] = "`buttonLabel` = '" . addslashes($caseStudy->getButtonLabel()) . "'";
        $insert[] = "`logo` = '" . addslashes($caseStudy->getLogo()) . "'";
        $insert[] = "`cardImage` = '" . addslashes($caseStudy->getCardImage()) . "'";
        $insert[] = "`headerImage` = '" . addslashes($caseStudy->getHeaderImage()) . "'";
        $insert[] = "`cardColour` = '" . addslashes($caseStudy->getCardColour()) . "'";
        $insert[] = "`isPublished` = '" . addslashes($caseStudy->isPublished()) . "'";
        $insert[] = "`regionID` = '" . addslashes($caseStudy->getRegionID()) . "'";

        $query = new SQL("UPDATE `case-studies` SET " . implode(', ', $insert) . " WHERE `id` = '{$caseStudy->getId()}'");
        $query->query();
    }

    public function getById(int $id): CaseStudy
    {
        return $this->getObjectWhere([
            "`id` = {$id}"
        ]);
    }

    /**
     * @param $caseStudyData
     * @return CaseStudy
     */
    private function buildProjectFromData($caseStudyData)
    {
        $caseStudy = new CaseStudy();
        $caseStudy->setId($caseStudyData['id']);
        $caseStudy->setTitle($caseStudyData['title']);
        $caseStudy->setIntroCardText($caseStudyData['introCardText']);
        $caseStudy->setIntroPageText($caseStudyData['introPageText']);
        $caseStudy->setMainText($caseStudyData['mainText']);
        $caseStudy->setProjectStartDate($caseStudyData['projectStartDate']);
        $caseStudy->setProjectEndDate($caseStudyData['projectEndDate']);
        $caseStudy->setUrl($caseStudyData['url']);
        $caseStudy->setButtonLabel($caseStudyData['buttonLabel']);
        $caseStudy->setLogo($caseStudyData['logo']);
        $caseStudy->setCardImage($caseStudyData['cardImage']);
        $caseStudy->setHeaderImage($caseStudyData['headerImage']);
        $caseStudy->setCardColour($caseStudyData['cardColour']);
        $caseStudy->setIsPublished($caseStudyData['isPublished']);
        if ($caseStudyData['regionID']) {
            $caseStudy->setRegion(
                (new CountryRegionRepository())->getById($caseStudyData['regionID'])
            );
        }

        return $caseStudy;
    }

    public function getAll()
    {
        return $this->getObjectsWhere(["1"]);
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `case-studies`");
        $query->query();
    }

    private function getObjectWhere($where)
    {
        $objects = $this->getObjectsWhere($where);
        if (count($objects) < 1)
            throw new DSI\NotFound();

        return $objects[0];
    }

    /**
     * @param $where
     * @param array $options
     * @return CaseStudy[]
     */
    private function getObjectsWhere($where, $options = [])
    {
        $caseStudies = [];
        $query = new SQL("SELECT 
            id, title
          , introCardText, introPageText, mainText
          , projectStartDate, projectEndDate
          , url, buttonLabel
          , logo, cardImage, headerImage
          , cardColour
          , isPublished
          , regionID
          FROM `case-studies`
          WHERE " . implode(' AND ', $where) . "
          ORDER BY `id` ASC
          " . ((isset($options['limit']) AND $options['limit'] > 0) ? "LIMIT {$options['limit']}" : '') . "
        ");
        foreach ($query->fetch_all() AS $dbCaseStudy) {
            $caseStudies[] = $this->buildProjectFromData($dbCaseStudy);
        }
        return $caseStudies;
    }
}