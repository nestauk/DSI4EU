<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    const TABLE = 'impact-tags';
    public $timestamps = false;
    protected $table = self::TABLE;
    protected $fillable = [self::Name];

    const Id = 'id';
    const Name = 'tag';
    const IsMain = 'isMain';
    const Order = 'order';

    const IsTechnologyMain = 'isTechnologyMain';
    const TechnologyOrder = 'technologyOrder';

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
}