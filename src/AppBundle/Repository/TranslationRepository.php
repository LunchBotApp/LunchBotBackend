<?php
/**
 * This file is part of LunchBotBackend.
 *
 * Copyright (c) 2018 Benoît Frisch, Haris Dzogovic, Philipp Machauer, Pierre Bonert, Xiang Rong Lin
 *
 * LunchBotBackend is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 *
 * LunchBotBackend is distributed in the hope that it will be useful,but WITHOUT ANY WARRANTY; without even the implied warranty ofMERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with LunchBotBackend If not, see <http://www.gnu.org/licenses/>.
 *
 */

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