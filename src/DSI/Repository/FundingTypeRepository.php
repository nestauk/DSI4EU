<?php

namespace DSI\Repository;

use DSI\Entity\FundingType;
use DSI\NotFound;

class FundingTypeRepository
{
    /** @var FundingType[] */
    private $objects;

    public function __construct()
    {
        $this->createFundingType(1, "Grant Funding");
        $this->createFundingType(2, "Investment / Social Investment");
        $this->createFundingType(3, "Incubators and Accelerators (with funding)");
        $this->createFundingType(4, "Non-financial support");
        $this->createFundingType(5, "Working spaces");
        $this->createFundingType(6, "Networking opportunities");
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
        $fundingType = new FundingType();
        $fundingType->setId($id);
        $fundingType->setTitle($title);
        $this->objects[] = $fundingType;
    }
}