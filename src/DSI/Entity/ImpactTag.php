<?php

namespace DSI\Entity;

class ImpactTag
{
    /** @var integer */
    private $id,
        $order,
        $technologyOrder;

    /** @var string */
    private $name;

    /** @var bool */
    private $isMain,
        $isTechnologyMain;

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int)$this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        if ($id <= 0)
            throw new \InvalidArgumentException('id: ' . $id);

        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string)$this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = (string)$name;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return (int)$this->order;
    }

    /**
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = (int)$order;
    }

    /**
     * @return bool
     */
    public function isMain(): bool
    {
        return (bool)$this->isMain;
    }

    /**
     * @param bool $isMain
     */
    public function setIsMain($isMain)
    {
        $this->isMain = (bool)$isMain;
    }

    /**
     * @return int
     */
    public function getTechnologyOrder(): int
    {
        return (int)$this->technologyOrder;
    }

    /**
     * @param int $technologyOrder
     * @return ImpactTag
     */
    public function setTechnologyOrder($technologyOrder)
    {
        $this->technologyOrder = (int)$technologyOrder;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTechnologyMain(): bool
    {
        return (bool)$this->isTechnologyMain;
    }

    /**
     * @param bool $isTechnologyMain
     * @return ImpactTag
     */
    public function setIsTechnologyMain($isTechnologyMain)
    {
        $this->isTechnologyMain = (bool)$isTechnologyMain;
        return $this;
    }
}