<?php

namespace DSI\Entity;

class Story
{
    /** @var integer */
    private $id;

    /** @var User */
    private $writer;

    /** @var string */
    private $title,
        $content,
        $time,
        $bgImage;

    /** @var StoryCategory */
    private $storyCategory;

    /**
     * @return int
     */
    public function getId()
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
     * @return User
     */
    public function getWriter()
    {
        return $this->writer;
    }

    /**
     * @param User $writer
     */
    public function setWriter(User $writer)
    {
        $this->writer = $writer;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return (string)$this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = (string)$title;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getTime()
    {
        return (string)$this->time;
    }

    /**
     * @param string $time
     */
    public function setTime($time)
    {
        $this->time = (string)$time;
    }

    /**
     * @return StoryCategory
     */
    public function getStoryCategory()
    {
        return $this->storyCategory;
    }

    /**
     * @return int
     */
    public function getStoryCategoryId()
    {
        return $this->storyCategory ? $this->storyCategory->getId() : 0;
    }

    /**
     * @param StoryCategory $storyCategory
     */
    public function setStoryCategory(StoryCategory $storyCategory)
    {
        $this->storyCategory = $storyCategory;
    }

    /**
     * @return string
     */
    public function getBgImage()
    {
        return (string)$this->bgImage;
    }

    /**
     * @param string $bgImage
     */
    public function setBgImage($bgImage)
    {
        $this->bgImage = (string)$bgImage;
    }
}