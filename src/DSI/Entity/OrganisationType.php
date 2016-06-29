<?php

namespace DSI\Entity;

class OrganisationType
{
    /** @var integer */
    private $id;

    /** @var string */
    private $name;

    /** @var int */
    private $order;
    
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
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return (int)$this->order;
    }

    /**
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = (int) $order;
    }
}