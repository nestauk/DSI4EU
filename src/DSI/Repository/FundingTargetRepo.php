<?php

namespace DSI\Repository;

use DSI\Entity\FundingTarget;
use DSI\NotFound;

class FundingTargetRepo
{
    /** @var FundingTarget[] */
    private $objects;

    public function __construct()
    {
        $this->createFundingType(1, "Government organisation");
        $this->createFundingType(2, "Research organisation");
        $this->createFundingType(3, "Startup");
        $this->createFundingType(4, "Social enterprise, charity or other non-profit");
        $this->createFundingType(5, "Individuals");
    }

    public function getById(int $id)
    {
        foreach($this->objects AS $object){
            if($object->getId() == $id)
                return $object;
        }

        throw new NotFound();
    }

    public function getAll()
    {
        return $this->objects;
    }

    /**
     * @param $id
     * @param $title
     */
    private function createFundingType($id, $title)
    {
        $fundingTarget = new FundingTarget();
        $fundingTarget->setId($id);
        $fundingTarget->setTitle($title);
        $this->objects[] = $fundingTarget;
    }
}