<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Meal;
use AppBundle\Entity\Restaurant as Restaurant;
use AppBundle\Entity\Rating as Rating;
use AppBundle\Entity\User as User;

/**
 * The repository handling the saving, deleting and fetching of ratings.
 *
 * @package AppBundle\Repository
 */
class RatingRepository extends BaseRepository
{
    /**
     * Return all ratings of a user with given id.
     *
     * @param int $id The internal id of a user
     * @return array An array of ratings
     */
    public function getAllByUserId($id)
    {
        return $this->findBy(['user' => $id]);
    }

    /**
     * Return all ratings of a meal with given id.
     *
     * @param int $id The internal id of a meal
     * @return array An Array of ratings
     */
    public function getAllByMealId(int $id)
    {
        return $this->findBy(['meal' => $id]);
    }

    /**
     * Return all ratings of all meals of a restaurant with given id.
     * @param int $id The internal id of a restaurant
     * @return array An array of ratings
     */
    public function getAllByRestaurantId(int $id)
    {
        $em = $this->getEntityManager();

        $meals = $em->getRepository(Meal::class)->getAllMealsByRestaurant($id);
        if (!is_null($meals) && sizeof($meals) > 0) {
            $qb = $this->createQueryBuilder('a');
            $qb->where('a.meal = :meal')
                ->setParameter('meal', $meals[0]);
            if (sizeof($meals) > 1) {
                for ($i = 1; $i < sizeof($meals); $i++) {
                    $qb->orWhere("a.meal = :meal$i")
                        ->setParameter("meal$i", $meals[$i]);
                }
            }
            return $qb->getQuery()->getResult();
        }
        else {
            return [];
        }
    }

    /**
     * Get all meals that the user has rated 5 stars previously from the meals that are available today
     * @param array $todayMeals The meals from today
     * @param User $user The user
     * @return array An array of meals that the user has rated 5 stars previously
     */
    public function getPrevious5Star($todayMeals, $user)
    {
        $meals = [];

        if (!is_null($user)) {
            foreach ($todayMeals as $meal) {
                if (!is_null($meal)) {
                    $rating = $this->findOneBy([
                        'user'   => $user->getId(),
                        'meal'   => $meal->getId(),
                        'value'     => '5'
                    ]);
                    if (!is_null($rating)) {
                        array_push($meals, $meal);
                    }
                }
            }
        }

        return $meals;
    }
}
