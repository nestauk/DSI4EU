<?php

namespace DSI\Entity;

class ImpactTag
{
    /** @var integer */
    private $id,
        $order;

    /** @var string */
    private $name;

    /** @var bool */
    private $isMain,
        $isImpact,
        $isTechnology;

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
     * @return bool
     */
    public function isImpact(): bool
    {
        return (bool)$this->isImpact;
    }

    /**
     * @param bool $isImpact
     * @return ImpactTag
     */
    public function setIsImpact($isImpact)
    {
        $this->isImpact = (bool)$isImpact;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTechnology(): bool
    {
        return (bool)$this->isTechnology;
    }

    /**
     * @param bool $isTechnology
     * @return ImpactTag
     */
    public function setIsTechnology($isTechnology)
    {
        $this->isTechnology = (bool)$isTechnology;
        return $this;
    }
}