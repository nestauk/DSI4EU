<?php

namespace DSI\Entity;

use DSI\NotFound;

class Translation
{
    const LANGUAGES = [
        'en',
        'de',
        'fr',
    ];
    const DEFAULT_LANGUAGE = 'en';

    /** @var string */
    private $index,
        $details;

    /** @var string[] */
    private $translations;

    /**
     * @return string
     */
    public function getIndex(): string
    {
        return (string)$this->index;
    }

    /**
     * @param string $index
     */
    public function setIndex($index)
    {
        $this->index = (string)$index;
    }

    /**
     * @return string
     */
    public function getDetails(): string
    {
        return (string)$this->details;
    }

    /**
     * @param string $details
     */
    public function setDetails(string $details)
    {
        $this->details = (string)$details;
    }

    /**
     * @param $lang
     * @return string
     * @throws NotFound
     */
    public function getTranslationFor($lang): string
    {
        if (isset($this->translations[$lang]) AND $this->translations[$lang]!='')
            return (string)$this->translations[$lang];
        else
            throw new NotFound('translation for ' . $lang);
    }

    /**
     * @param $lang
     * @return string
     * @throws NotFound
     */
    public function getTranslationOrEmptyFor($lang): string
    {
        if (isset($this->translations[$lang]) AND $this->translations[$lang]!='')
            return (string)$this->translations[$lang];
        else
            return '';
    }

    /**
     * @param string $lang
     * @param string $translation
     */
    public function setTranslationFor($lang, $translation)
    {
        $this->translations[$lang] = (string)$translation;
    }
}