<?php

namespace AppBundle\Repository;
use AppBundle\Entity\Country;

/**
 * CountryRepository
 */
class CountryRepository extends BaseRepository
{
    /**
     * @param $name
     * @return Country|null|object
     */
    public function getByName($name)
    {
        return $this->getEntityManager()->getRepository(Country::class)->findOneBy(['name' => $name]);
    }
}