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

use AppBundle\Entity\Meal;
use AppBundle\Entity\Restaurant;
use DateTime;

/**
 * The repository handling the saving, deleting and fetching of meals
 *
 * @package AppBundle\Repository
 */
class MealRepository extends BaseRepository
{
    /**
     * Return a different meal with the same name.
     * Null if no such meal exists
     *
     * @param Meal $meal The name of a meal
     * @return Meal|null The meal with the name
     */
    public function getDifferentByName($meal)
    {
        $mealArray =  $this->createQueryBuilder('a')
            ->setMaxResults(1)
            ->where('a.name = :name')
            ->andWhere('a.id != :id')
            ->setParameter('name', $meal->getName())
            ->setParameter('id', $meal->getId())
            ->getQuery()
            ->getResult();

        if (sizeof($mealArray) > 0) {
            return $mealArray[0];
        }
    }

    /**
     * Return all upcoming meals.
     *
     * @return array An array of all upcoming meals
     */
    public function getAllUpcoming()
    {
        $today = new DateTime();
        $today->setTime(0, 0);

        return $this->getEntityManager()
            ->getRepository(Meal::class)
            ->createQueryBuilder('a')
            ->where('a.date >= :today')
            ->orderBy('a.date', 'ASC')
            ->addOrderBy('a.restaurant', 'ASC')
            ->addOrderBy('a.price', 'ASC')
            ->setParameter('today', $today)
            ->getQuery()
            ->getResult();
    }

    /**
     * Return all uncategorized meals
     *
     * @return array An array with all uncategorized meals
     */
    public function getAllUncategorized()
    {
        $today = new DateTime();
        $today->setTime(0, 0);

        return $this->getEntityManager()
            ->getRepository(Meal::class)
            ->createQueryBuilder('a')
            ->leftJoin('a.categories', 'categories')
            ->where('a.date >= :today')
            ->andWhere($this->getEntityManager()->createQueryBuilder()->expr()->isNull('categories'))
            ->orderBy('a.date', 'ASC')
            ->addOrderBy('a.restaurant', 'ASC')
            ->addOrderBy('a.price', 'ASC')
            ->setParameter('today', $today)
            ->getQuery()
            ->getResult();
    }

    /**
     * Returns all meals that are available today in a restaurant with given id.
     *
     * @param int $id The internal id of a restaurant
     * @return array An array of meals
     */
    public function getAllTodayMealsByRestaurant($id)
    {
        $today = new DateTime();
        $today->setTime(0, 0);
        $restaurant = $this->getEntityManager()->getRepository(Restaurant::class)->find($id);

        return $this->getEntityManager()
            ->getRepository(Meal::class)
            ->createQueryBuilder('a')
            ->where('a.restaurant = :restaurant')
            ->andWhere('a.date = :today')
            ->addOrderBy('a.restaurant', 'ASC')
            ->addOrderBy('a.price', 'ASC')
            ->setParameter('today', $today)
            ->setParameter('restaurant', $restaurant)
            ->getQuery()
            ->getResult();
    }

    /**
     * Returns all meals from restaurant with given id.
     *
     * @param int $id The internal id of a restaurant
     * @return array An array of meals
     */
    public function getAllMealsByRestaurant($id)
    {
        $restaurant = $this->getEntityManager()->getRepository(Restaurant::class)->find($id);

        return $this->getEntityManager()
            ->getRepository(Meal::class)
            ->createQueryBuilder('a')
            ->where('a.restaurant = :restaurant')
            ->setParameter('restaurant', $restaurant)
            ->getQuery()
            ->getResult();
    }

    /**
     * Returns all meals that are available today.
     *
     * @return array An array of meals
     */
    public function getAllTodayMeals()
    {
        $today = new DateTime();
        $today->setTime(0, 0);

        return $this->getEntityManager()
            ->getRepository(Meal::class)
            ->createQueryBuilder('a')
            ->where('a.date = :today')
            ->addOrderBy('a.restaurant', 'ASC')
            ->addOrderBy('a.price', 'ASC')
            ->setParameter('today', $today)
            ->getQuery()
            ->getResult();
    }

    /**
     * Returns all meals that are available today.
     *
     * @param $min
     * @param $max
     * @return mixed
     */
    public function getAllTodayMealsPrice($min, $max)
    {
        $today = new DateTime();
        $today->setTime(0, 0);

        return $this->getEntityManager()
            ->getRepository(Meal::class)
            ->createQueryBuilder('a')
            ->where('a.date = :today')
            ->andWhere('a.price >= :min')
            ->andWhere('a.price <= :max')
            ->addOrderBy('a.restaurant', 'ASC')
            ->addOrderBy('a.price', 'ASC')
            ->setParameter('today', $today)
            ->setParameter('min', $min)
            ->setParameter('max', $max)
            ->getQuery()
            ->getResult();
    }


    /**
     * Returns all meals that are available today.
     *
     * @return array An array of meals
     */
    /*public function getThreeTodayMeals()
    {
        $today = new DateTime();
        $today->setTime(0, 0);

        return $this->getEntityManager()
            ->getRepository(Meal::class)
            ->createQueryBuilder('a')
            ->where('a.date = :today')
            ->addOrderBy('a.restaurant', 'ASC')
            ->addOrderBy('a.price', 'ASC')
            ->setParameter('today', $today)
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }*/

    /**
     * Returns a random meal that is available today.
     *
     * @return Meal A meal that is available today
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    /*public function getRandomMeal()
    {
        $today = new DateTime();
        $today->setTime(0, 0);


        $max = $this->getEntityManager()
            ->createQuery('SELECT MAX(a.id) FROM AppBundle:Meal a')->getSingleScalarResult();

        return $this->getEntityManager()->createQuery('
            SELECT a FROM AppBundle:Meal a 
            WHERE a.id >= :rand AND a.date = :today
            ORDER BY a.id ASC
            ')
            ->setParameter('rand', rand(0, $max))
            ->setParameter('today', $today)
            ->setMaxResults(1)
            ->getSingleResult();
    }*/
}
