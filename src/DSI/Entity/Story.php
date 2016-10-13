<?php

namespace DSI\Entity;

class Story
{
    /** @var integer */
    private $id;

    /** @var User */
    private $author;

    /** @var string */
    private $title,
        $cardShortDescription,
        $content,
        $time,
        $featuredImage,
        $mainImage;

    /** @var StoryCategory */
    private $storyCategory;

    /** @var bool */
    private $isPublished;

    /** @var string */
    private $datePublished;

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
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param User $author
     */
    public function setAuthor(User $author)
    {
        $this->author = $author;
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
    public function getMainImage()
    {
        return (string)$this->mainImage;
    }

    /**
     * @param string $mainImage
     */
    public function setMainImage($mainImage)
    {
        $this->mainImage = (string)$mainImage;
    }

    /**
     * @return string
     */
    public function getFeaturedImage()
    {
        return (string)$this->featuredImage;
    }

    /**
     * @param string $featuredImage
     */
    public function setFeaturedImage($featuredImage)
    {
        $this->featuredImage = (string)$featuredImage;
    }

    /**
     * @return boolean
     */
    public function isPublished()
    {
        return (bool)$this->isPublished;
    }

    /**
     * @param boolean $isPublished
     */
    public function setIsPublished($isPublished)
    {
        $this->isPublished = (bool)$isPublished;
    }

    /**
     * @param null $format
     * @return bool|string
     */
    public function getDatePublished($format = NULL)
    {
        if ($format) {
            $date = strtotime($this->datePublished);
            return date($format, $date);
        } else
            return (string)$this->datePublished;
    }

    /**
     * @param string $datePublished
     */
    public function setDatePublished($datePublished)
    {
        $this->datePublished = (string)$datePublished;
    }

    /**
     * @return string
     */
    public function getCardShortDescription(): string
    {
        return (string)$this->cardShortDescription;
    }

    /**
     * @param string $cardShortDescription
     */
    public function setCardShortDescription($cardShortDescription)
    {
        $this->cardShortDescription = (string)$cardShortDescription;
    }
}