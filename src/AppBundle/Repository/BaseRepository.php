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

use Doctrine\ORM\EntityRepository;

/**
 * Abstract repository class with the basic functions of all repositories.
 *
 * @package AppBundle\Repository
 */
class BaseRepository extends EntityRepository
{
    /**
     * Create the field in the database if it doesn't exist, otherwise update it.
     *
     * @param $object object The object that will be saved into the database.
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save($object)
    {
        $databaseObject = $this->findOneBy(['id' => $object->getId()]);
        $em             = $this->getEntityManager();
        if (is_null($databaseObject)) {
            $em->persist($object);
        } else {
            $em->merge($object);
        }
        $em->flush();
    }

    /**
     * Removes the object with given id from the database.
     * Does nothing if no object with given id exists.
     *
     * @param $id int The id of the object that will be deleted from the database.
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete($id)
    {
        $em     = $this->getEntityManager();
        $object = $em->getReference($this->getClassName(), $id);
        if (!is_null($object)) {
            $em->remove($object);
            $em->flush();
        }
    }

    /**
     * Returns all objects of a table.
     *
     * @return array An array of all objects.
     */
    public function getAll()
    {
        return $this->findAll();
    }

    /**
     * Returns the object with the given id.
     *
     * @param int $id The id of the object.
     * @return null|object Null if no object with given id exists, otherwise the object.
     */
    public function getById($id)
    {
        return $this->find($id);
    }
}
