<?php

namespace DSI\Repository;

use DSI\Entity\FundingType;
use DSI\NotFound;

class FundingTypeRepo
{
    /** @var FundingType[] */
    private $objects;

    public function __construct()
    {
        $this->createFundingType(1, "Grant Funding", 'rgba(115, 23, 214, 0.96)');
        $this->createFundingType(2, "Investment / Social Investment", '#ffb800');
        $this->createFundingType(3, "Incubators and Accelerators (with funding)", '#18233f');
        $this->createFundingType(4, "Non-financial support", '#dd3ea4');
        $this->createFundingType(5, "Working spaces", '#33d6ff');
        $this->createFundingType(6, "Networking opportunities", '#1dc9a0');
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
     * @param int $id
     * @param string $title
     * @param string $color
     */
    private function createFundingType(int $id, string $title, string $color)
    {
        $fundingType = new FundingType();
        $fundingType->setId($id);
        $fundingType->setTitle($title);
        $fundingType->setColor($color);
        $this->objects[] = $fundingType;
    }
}