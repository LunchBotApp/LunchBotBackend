<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Language as Language;

/**
 * LanguageRepository
 */
class LanguageRepository extends BaseRepository
{
	/**
     * Returns the language with that locale
     *
     * @param string $locale the language's locale code
     * @return Language the language with that locale
     */
	public function getByLocale($locale)
	{
		return $this->findOneBy(['locale' => $locale]);
	}
}