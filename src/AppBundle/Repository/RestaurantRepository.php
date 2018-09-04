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

use AppBundle\Entity\City;
use AppBundle\Entity\Meal as Meal;
use AppBundle\Entity\Rating as Rating;
use AppBundle\Entity\Restaurant;
use DateTime;

/**
 * The repository handling the saving, deleting and fetching of restaurants.
 *
 * @package AppBundle\Repository
 */
class RestaurantRepository extends BaseRepository
{
    /**
     * Return all Restaurants.
     *
     * @return mixed All restaurants
     */
    public function getAllRestaurants()
    {
        return $this->createQueryBuilder('c')->addOrderBy('c.name', 'ASC')->getQuery()->execute();
    }

    /**
     * Returns the average rating of meals from this restaurant.
     *
     * @param $id int The internal id of a restaurant.
     * @return float The average rating.
     */
    public function getAverageRating($id)
    {
        $ratings       = $this->getRatings($id);
        if (is_null($ratings) || sizeof($ratings) == 0) {
            return 0;
        } else {
            $ratingsSum    = 0;
            $ratingsLenght = sizeof($ratings);
            foreach ($ratings as $rating) {
                $ratingsSum += $rating->getValue();
            }

            return $ratingsSum / $ratingsLenght;
        }
    }

    /**
     * Returns all ratings of a restaurant with given id.
     *
     * @param $id int The internal id of a restaurant
     * @return array An array of ratings
     */
    public function getRatings(int $id)
    {
        return $this->getEntityManager()->getRepository(Rating::class)->getAllByRestaurantId($id);
    }

    /**
     * Returns all meals that are available today in a restaurant with given id.
     *
     * @param int $id The internal id of a restaurant
     * @return array An array of meals
     */
    public function getTodayMeals($id)
    {
        $restaurant = $this->getEntityManager()->getRepository(Restaurant::class)->find($id);

        $today = new DateTime();
        $today->setTime(0, 0);

        $tomorrow = new DateTime("tomorrow");
        $tomorrow->setTime(0, 0);

        return $this->getEntityManager()
            ->getRepository(Meal::class)
            ->createQueryBuilder('a')
            ->where('a.restaurant = :restaurant')
            ->andWhere('a.date >= :today')
            ->andWhere('a.date < :tomorrow')
            ->orderBy('a.place', 'ASC')
            ->addOrderBy('a.price', 'ASC')
            ->setParameter('restaurant', $restaurant)
            ->setParameter('today', $today)
            ->setParameter('tomorrow', $tomorrow)
            ->getQuery()
            ->getResult();
    }

    /**
     * Returns all restaurants from a city
     * @param int $id The internal id of a city
     * @return array All restaurants from a city
     */
    public function getAllRestaurantsByCity($id)
    {
        return $this->findBy(["city" => $id]);
    }
}
