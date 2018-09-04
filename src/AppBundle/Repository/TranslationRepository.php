<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Language as Language;
use AppBundle\Entity\Translation as Translation;

/**
 * TranslationRepository
 */
class TranslationRepository extends BaseRepository
{
    /**
     * Returns the translation of $message into the language with $locale
     *
     * @param $locale
     * @param $message
     * @return Translation
     */
    public function getByLocaleAndKey($locale, $message)
    {
        $localeTranslations = $this->getByLocale($locale);
        foreach ($localeTranslations as $translation) {
            if ($translation->getMessage()->getMessageKey() == $message) {
                return $translation;
            }
        }
    }

    /**
     * Returns all translations with that locale
     *
     * @param string $locale the internal id to search by
     * @return array an array with all translations with the specified locale
     */
    public function getByLocale($locale)
    {
        $language = $this->getEntityManager()->getRepository(Language::class)->getByLocale($locale);
        if ($language) {
            return $language->getTranslations();
        } else {
            return ["undefined"];
        }
    }
}