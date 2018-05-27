<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    const TABLE = 'impact-tags';
    public $timestamps = false;
    protected $table = self::TABLE;

    const Id = 'id';
    const Order = 'order';
    const Name = 'tag';
    const IsMain = 'isMain';

    const IsImpact = 'isImpact';
    const IsTechnology = 'isTechnology';

    public function getId()
    {
        return $this->{self::Id};
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        if ($id <= 0)
            throw new \InvalidArgumentException(self::Id . ': ' . $id);

        $this->{self::Id} = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string)$this->{self::Name};
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->{self::Name} = $name;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return (int)$this->{self::Order};
    }

    /**
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->{self::Order} = (int)$order;
    }

    /**
     * @return bool
     */
    public function isMain(): bool
    {
        return (bool)$this->{self::IsMain};
    }

    /**
     * @param bool $isMain
     */
    public function setIsMain($isMain)
    {
        $this->{self::IsMain} = (bool)$isMain;
    }

    /**
     * @return bool
     */
    public function isImpact(): bool
    {
        return (bool)$this->{self::IsImpact};
    }

    /**
     * @param bool $isImpact
     */
    public function setIsImpact($isImpact)
    {
        $this->{self::IsImpact} = (bool)$isImpact;
    }

    /**
     * @return bool
     */
    public function isTechnology(): bool
    {
        return (bool)$this->{self::IsTechnology};
    }

    /**
     * @param bool $isTechnology
     */
    public function setIsTechnology($isTechnology)
    {
        $this->{self::IsTechnology} = (bool)$isTechnology;
    }
}