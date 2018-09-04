<?php

namespace AppBundle\Repository;

use AppBundle\Entity\City;

/**
 * CityRepository
 */
class CityRepository extends BaseRepository
{
    /**
     * @param $name
     * @return City|null|object
     */
    public function getByName($name)
    {
        return $this->getEntityManager()->getRepository(City::class)->findOneBy(['name' => $name]);
    }
}