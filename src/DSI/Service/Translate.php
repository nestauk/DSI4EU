<?php
namespace DSI\Service;

use DSI\Entity\Translation;
use DSI\NotEnoughData;
use DSI\NotFound;
use DSI\Repository\TranslationRepository;

class Translate
{
    /** @var Translation[] */
    private static $translations;

    /** @var TranslationRepository */
    private static $translationRepository;

    private static $currentLang;

    /**
     * @param string $lang
     * @param string $index
     * @return string
     * @throws NotFound
     */
    public static function getTranslationFor($lang, $index)
    {
        self::fetchTranslations();

        if (isset(self::$translations[$index])) {
            try {
                return self::$translations[$index]->getTranslationFor($lang);
            } catch (NotFound $e) {
                return self::$translations[$index]->getTranslationFor(Translation::DEFAULT_LANGUAGE);
            }
        } else {
            return $index;
        }
    }

    /**
     * @param $index
     * @return string
     * @throws NotEnoughData
     * @throws NotFound
     */
    public static function getTranslation($index)
    {
        if (!isset(self::$currentLang))
            throw new NotEnoughData('current lang not set');

        return self::getTranslationFor(self::$currentLang, $index);
    }

    private static function fetchTranslations()
    {
        if (!isset(self::$translationRepository))
            self::$translationRepository = new TranslationRepository();

        if (!isset(self::$translations)) {
            $translations = self::$translationRepository->getAll();

            foreach ($translations AS $translation) {
                self::$translations[$translation->getIndex()] = $translation;
            }
        }
    }

    /**
     * @param string $currentLang
     * @throws NotFound
     */
    public static function setCurrentLang($currentLang)
    {
        if (!in_array($currentLang, Translation::LANGUAGES))
            throw new NotFound('language not found: ' . $currentLang);

        self::$currentLang = $currentLang;
    }

    /**
     * @return string
     */
    public static function getCurrentLang()
    {
        return self::$currentLang;
    }
}