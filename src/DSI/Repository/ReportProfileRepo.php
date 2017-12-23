<?php

namespace DSI\Repository;

use DSI;
use DSI\Service\SQL;
use DSI\Entity\ReportProfile;

class ReportProfileRepo
{
    public function insert(ReportProfile $report)
    {
        $insert = array();
        $insert[] = "`byUserID` = '" . (int)($report->getByUserId()) . "'";
        $insert[] = "`reportedUserId` = '" . (int)($report->getReportedUserId()) . "'";
        $insert[] = "`comment` = '" . addslashes($report->getComment()) . "'";
        $insert[] = "`time` = NOW()";
        $insert[] = "`reviewedByUserID` = '" . (int)($report->getReviewedByUserId()) . "'";
        $insert[] = "`reviewedTime` = '" . addslashes($report->getReviewedTime()) . "'";
        $insert[] = "`review` = '" . addslashes($report->getReview()) . "'";
        $query = new SQL("INSERT INTO `report-profile` SET " . implode(', ', $insert) . "");
        $query->query();

        $report->setId($query->insert_id());
    }

    public function save(ReportProfile $report)
    {
        $query = new SQL("SELECT id FROM `report-profile` WHERE id = '{$report->getId()}' LIMIT 1");
        $existingEntity = $query->fetch();
        if (!$existingEntity)
            throw new DSI\NotFound('id: ' . $report->getId());

        $insert = array();
        $insert[] = "`byUserID` = '" . (int)($report->getByUserId()) . "'";
        $insert[] = "`reportedUserId` = '" . (int)($report->getReportedUserId()) . "'";
        $insert[] = "`comment` = '" . addslashes($report->getComment()) . "'";
        $insert[] = "`reviewedByUserID` = '" . (int)($report->getReviewedByUserId()) . "'";
        $insert[] = "`reviewedTime` = '" . addslashes($report->getReviewedTime()) . "'";
        $insert[] = "`review` = '" . addslashes($report->getReview()) . "'";

        $query = new SQL("UPDATE `report-profile` SET " . implode(', ', $insert) . " WHERE `id` = '{$report->getId()}'");
        $query->query();
    }

    public function getById(int $id): ReportProfile
    {
        return $this->getObjectWhere([
            "`id` = {$id}"
        ]);
    }

    /**
     * @param array $report
     * @return ReportProfile
     */
    private function buildObjectFromData($report)
    {
        $userRepo = new UserRepo();

        $reportObj = new ReportProfile();
        $reportObj->setId($report['id']);
        if ($report['byUserID'])
            $reportObj->setByUser($userRepo->getById($report['byUserID']));
        if ($report['reportedUserId'])
            $reportObj->setReportedUser($userRepo->getById($report['reportedUserId']));
        $reportObj->setComment($report['comment']);
        $reportObj->setTime($report['time']);

        if ($report['reviewedByUserID'])
            $reportObj->setReviewedByUser($userRepo->getById($report['reviewedByUserID']));
        $reportObj->setReviewedTime($report['reviewedTime']);
        $reportObj->setReview($report['review']);

        return $reportObj;
    }

    /** @return ReportProfile[] */
    public function getAll()
    {
        return $this->getObjectsWhere(["1"]);
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `report-profile`");
        $query->query();
    }

    /**
     * @param array $where
     * @return ReportProfile
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
     * @param array $where
     * @return ReportProfile[]
     */
    private function getObjectsWhere($where)
    {
        $objects = [];
        $query = new SQL("SELECT 
            id, byUserID, reportedUserId, comment, time
          , reviewedByUserID, reviewedTime, review
          FROM `report-profile` WHERE " . implode(' AND ', $where) . "");
        foreach ($query->fetch_all() AS $dbObject) {
            $objects[] = $this->buildObjectFromData($dbObject);
        }
        return $objects;
    }
}