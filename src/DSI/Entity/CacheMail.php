<?php

namespace DSI\Entity;

use DSI\Service\Mailer;

class CacheMail
{
    /** @var integer */
    private $id;

    /** @var Mailer */
    private $content;

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
     * @return Mailer
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param Mailer $content
     */
    public function setContent(Mailer $content)
    {
        $this->content = $content;
    }
}