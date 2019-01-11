<?php

namespace DSI\Repository;

use DSI;
use DSI\Entity\CaseStudy;
use DSI\Service\SQL;

class CaseStudyRepo
{
    public function insert(CaseStudy $caseStudy)
    {
        $insert = array();
        $insert[] = "`title` = '" . addslashes($caseStudy->getTitle()) . "'";
        $insert[] = "`introCardText` = '" . addslashes($caseStudy->getIntroCardText()) . "'";
        $insert[] = "`introPageText` = '" . addslashes($caseStudy->getIntroPageText()) . "'";
        $insert[] = "`infoText` = '" . addslashes($caseStudy->getInfoText()) . "'";
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
        $insert[] = "`isFeaturedOnSlider` = '" . addslashes($caseStudy->isFeaturedOnSlider()) . "'";
        $insert[] = "`isFeaturedOnHomePage` = '" . ((int)$caseStudy->getPositionOnFirstPage()) . "'";
        $insert[] = "`projectID` = '" . ((int)$caseStudy->getProjectID()) . "'";
        $insert[] = "`organisationID` = '" . ((int)$caseStudy->getOrganisationID()) . "'";

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
        $insert[] = "`infoText` = '" . addslashes($caseStudy->getInfoText()) . "'";
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
        $insert[] = "`isFeaturedOnSlider` = '" . addslashes($caseStudy->isFeaturedOnSlider()) . "'";
        $insert[] = "`isFeaturedOnHomePage` = '" . ((int)$caseStudy->getPositionOnFirstPage()) . "'";
        $insert[] = "`projectID` = '" . ((int)$caseStudy->getProjectID()) . "'";
        $insert[] = "`organisationID` = '" . ((int)$caseStudy->getOrganisationID()) . "'";

        $query = new SQL("UPDATE `case-studies` SET " . implode(', ', $insert) . " WHERE `id` = '{$caseStudy->getId()}'");

        $query->query();
    }

    public function getById(int $id): CaseStudy
    {
        return $this->getObjectWhere([
            "`id` = {$id}"
        ]);
    }

    public function getByPositionOnHomePage(int $position): CaseStudy
    {
        return $this->getObjectWhere([
            "`isFeaturedOnHomePage` = {$position}"
        ]);
    }

    public function searchByTitle(string $name, int $limit = 0)
    {
        return $this->getObjectsWhere([
            "title LIKE '%" . addslashes($name) . "%'"
        ], [
            "limit" => $limit
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
        $caseStudy->setInfoText($caseStudyData['infoText']);
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
        $caseStudy->setIsFeaturedOnSlider($caseStudyData['isFeaturedOnSlider']);
        $caseStudy->setPositionOnFirstPage($caseStudyData['isFeaturedOnHomePage']);
        if ($caseStudyData['projectID']) {
            try {
                $caseStudy->setProject((new ProjectRepoInAPC())->getById($caseStudyData['projectID']));
            } catch (DSI\NotFound $e) {
            }
        }
        if ($caseStudyData['organisationID']) {
            try {
                $caseStudy->setOrganisation((new OrganisationRepoInAPC())->getById($caseStudyData['organisationID']));
            } catch (DSI\NotFound $e) {
            }
        }

        return $caseStudy;
    }

    public function getAll()
    {
        return $this->getObjectsWhere(["1"]);
    }

    public function getAllPublished()
    {
        return $this->getObjectsWhere([
            "`isPublished` = 1"
        ]);
    }

    public function getPublishedLast($limit)
    {
        return $this->getObjectsWhere([
            "`isPublished` = 1"
        ], ['limit' => $limit]);
    }

    public function getAllPublishedForSlider()
    {
        return $this->getObjectsWhere([
            "`isPublished` = 1",
            "`isFeaturedOnSlider` = 1",
        ]);
    }

    public function getSliderStudiesLast($limit)
    {
        return $this->getObjectsWhere([
            "`isPublished` = 1",
            "`isFeaturedOnSlider` = 1",
        ], [
            "limit" => $limit
        ]);
    }

    public function getHomePageStudiesLast($limit)
    {
        return $this->getObjectsWhere([
            "`isPublished` = 1",
            "`isFeaturedOnHomePage` > 0",
        ], [
            "limit" => $limit
        ]);
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
          , introCardText, introPageText, infoText, mainText
          , projectStartDate, projectEndDate
          , url, buttonLabel
          , logo, cardImage, headerImage
          , cardColour
          , isPublished, isFeaturedOnSlider, isFeaturedOnHomePage
          , projectID, organisationID
          FROM `case-studies`
          WHERE " . implode(' AND ', $where) . "
          ORDER BY `id` DESC
          " . ((isset($options['limit']) AND $options['limit'] > 0) ? "LIMIT {$options['limit']}" : '') . "
        ");
        foreach ($query->fetch_all() AS $dbCaseStudy) {
            $caseStudies[] = $this->buildProjectFromData($dbCaseStudy);
        }
        return $caseStudies;
    }
}
