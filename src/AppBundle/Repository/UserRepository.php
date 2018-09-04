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

use AppBundle\Entity\Category as Category;
use AppBundle\Entity\Rating as Rating;
use AppBundle\Entity\Restaurant as Restaurant;
use AppBundle\Entity\User as User;
use AppBundle\Entity\Meal as Meal;
use AppBundle\Entity\City;
use AppBundle\Entity\Settings as Settings;

/**
 * The repository handling the saving, deleting and fetching of Users.
 *
 * @package AppBundle\Repository
 */
class UserRepository extends BaseRepository
{
    /**
     * Returns a user with the same userId.
     *
     * @param string $id The userId of an user
     * @return User A User
     */
    public function getByUserId($id)
    {
        return $this->findOneBy(["userId" => $id]);
    }

    /**
     * Returns the average rating a user with given id.
     *
     * @param int $id The internal id of a user
     * @return int|float The average rating
     */
    public function getAverageRating(int $id)
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
     * Returns all ratings of a user
     *
     * @param int $id The internal id of an user
     * @return array An array of ratings
     */
    public function getRatings($id)
    {
        return $this->getEntityManager()->getRepository(Rating::class)->getAllByUserId($id);
    }

    /**
     * Calculates the 3 best meals for a user.
     * @param array      $todayMeals The meals that are available today
     * @param User       $user The user
     * @param boolean    $usePriceRange Whether the price range setting of the user should be used or not
     * @param City    $location The city for this suggestion
     * @return array An array with 3 meals
     */
    public function getBestMatch($todayMeals, $user, $usePriceRange = true, $location = null)
    {
        //Number of how many meals should get returned
        $numberOfSuggestedMeals = 3;

        //Get meals that were rated 5 stars previously
        $bestMeals = $this->getEntityManager()->getRepository(Rating::class)->getPrevious5Star($todayMeals, $user);

        //Remove previous 5 star meals that don't fit the settings of user
        $numberOfMeals = sizeof($bestMeals);
        for ($i = 0; $i < $numberOfMeals; $i++) {
            if (!$this->fitsSettings($bestMeals[$i], $user->getSettings(), $usePriceRange, $location)) {
                unset($bestMeals[$i]);
            }
        }
        //reassign keys
        $bestMeals = array_values($bestMeals);

        //remove the meals that are already chosen
        $filteredTodayMeals = array_diff($todayMeals, $bestMeals);
        //reassign array keys
        $filteredTodayMeals = array_values($filteredTodayMeals);

        if (sizeof($bestMeals) < $numberOfSuggestedMeals) {
            $points = $this->getAllMatchPoints($filteredTodayMeals, $user, $usePriceRange, $location);

            //Fill the empty spots in array with meals with the most points
            arsort($points);
            $highestPointsKey = array_slice(array_keys($points), 0, $numberOfSuggestedMeals - sizeof($bestMeals));

            for ($i = 0; $i < sizeof($highestPointsKey); $i++) {
                array_push($bestMeals, $filteredTodayMeals[$highestPointsKey[$i]]);
            }
            return $bestMeals;
        } elseif (sizeof($bestMeals) > $numberOfSuggestedMeals) {
            //randomize returned meals
            shuffle($bestMeals);
            return array_slice($bestMeals, 0, 3);
        } else {
            return $bestMeals;
        }
    }

    /**
     * @param array $todayMeals
     * @param User $user
     * @param boolean    $usePriceRange Whether the price range setting of the user should be used or not
     * @param City|null    $location The city for this suggestion
     * @return array
     */
    public function getAllMatchPoints($todayMeals, $user, $usePriceRange, $location)
    {
        //get all restaurants of the city of user, if no city set get all.
        $restaurantRepo = $this->getEntityManager()->getRepository(Restaurant::class);
        $settings = $user->getSettings();
        if (!is_null($location)) {
            $restaurants = $restaurantRepo->getAllRestaurantsByCity($location->getId());
        } elseif (is_null($settings)
            || is_null($settings->getLocation())
            || is_null($settings->getLocation()->getCity())) {
            $restaurants = $restaurantRepo->getAll();
        } else {
            $restaurants = $restaurantRepo->getAllRestaurantsByCity($settings->getLocation()->getCity()->getId());
        }

        //calculate points of each category for each restaurant with the ratings of user
        $ratings = $this->getRatings($user->getId());
        $categories = $this->getEntityManager()->getRepository(Category::class)->getAll();
        //category points of a restaurant from a user
        $restaurantCategoryPoints = $this->getCategoryPointsOfAllRestaurants($ratings, $categories, $restaurants);
        //overall category points from a user
        $categoryPoints = $this->getCategoryPoints($ratings, $categories);

        //calculate the average points of each meals based on the category points for the restaurant of the meal
        $points = array_fill(0, sizeof($todayMeals), 0);
        for ($i = 0; $i < sizeof($todayMeals); $i++) {
            //only calculate points if user has no settings or if meal fits the settings of user
            if (is_null($settings) || $this->fitsSettings($todayMeals[$i], $settings, $usePriceRange, $location)) {
                //TODO proper handling of meals without categories
                //meals without categories get 0 points
                $mealCategories = $todayMeals[$i]->getCategories();
                $restaurantIndex = array_search($todayMeals[$i]->getRestaurant(), $restaurants);
                if (!is_null($mealCategories) && sizeof($mealCategories) > 0 && is_numeric($restaurantIndex)) {
                    //add up the total amount of points for a meal
                    foreach ($mealCategories as $category) {
                        $categoryIndex = array_search($category, $categories);
                        $points[$i] += $restaurantCategoryPoints[$restaurantIndex][$categoryIndex];
                    }
                    // use the overall category points if no category points exists for a restaurant
                    if ($points[$i] == 0) {
                        foreach ($mealCategories as $category) {
                            $categoryIndex = array_search($category, $categories);
                            $points[$i] += $categoryPoints[$categoryIndex];
                        }
                    }
                    //average the amount of points with number of categories of meal
                    $points[$i] = $points[$i] /sizeof($mealCategories);
                }
            }
        }

        return $points;
    }

    /**
     * @param array $ratings
     * @param array $categories
     * @param array $restaurants
     * @return array
     */
    private function getCategoryPointsOfAllRestaurants($ratings, $categories, $restaurants)
    {
        //initialize array with category points set to 0 for each restaurant
        $points = array_fill(0, sizeof($restaurants), array_fill(0, sizeof($categories), 0));
        //count how many ratings exists of a category for a restaurant
        $pointsEntries = array_fill(0, sizeof($restaurants), array_fill(0, sizeof($categories), 0));

        //add up the total amount of points of each category for each restaurant
        for ($i = 0; $i < sizeof($ratings); $i++) {
            //TODO proper handling of meals without categories
            //skip ratings of meals without categories
            if (!is_null($ratings[$i]->getMeal()->getCategories())) {
                $mealCategories = $ratings[$i]->getMeal()->getCategories();
                $mealRestaurant = $ratings[$i]->getMeal()->getRestaurant();
                foreach ($mealCategories as $mealCategory) {
                    $categoryIndex                                   = array_search($mealCategory, $categories);
                    $restaurantIndex                                 = array_search($mealRestaurant, $restaurants);
                    if (is_numeric($categoryIndex) && is_numeric($restaurantIndex)) {
                        $points[$restaurantIndex][$categoryIndex]        += $ratings[$i]->getValue();
                        $pointsEntries[$restaurantIndex][$categoryIndex] += 1;
                    }
                }
            }
        }

        //average out the points of a category with the number of ratings
        for ($r = 0; $r < sizeof($restaurants); $r++) {
            for ($c = 0; $c < sizeof($categories); $c++) {
                if ($pointsEntries[$r][$c] > 0) {
                    $points[$r][$c] = $points[$r][$c] / $pointsEntries[$r][$c];
                }
            }
        }
        return $points;
    }

    /**
     *
     * @param array $ratings The ratings which need to be averaged per category
     * @param array $categories The categories for which the points have to be calculated
     * @return array An array with a category array and point array inside
     */
    private function getCategoryPoints($ratings, $categories)
    {
        //the points for a category
        $points = array_fill(0, sizeof($categories), 0);
        //count how many ratings exists for a category
        $pointsEntries = array_fill(0, sizeof($categories), 0);

        //add up the total amount of points for each category
        for ($i = 0; $i < sizeof($ratings); $i++) {
            //TODO proper handling of meals without categories
            //skip ratings of meals without categories
            if (!is_null($ratings[$i]->getMeal()->getCategories())) {
                $mealCategories = $ratings[$i]->getMeal()->getCategories();
                foreach ($mealCategories as $mealCategory) {
                    $index = array_search($mealCategory, $categories);
                    if (is_numeric($index)) {
                        $points[$index] += $ratings[$i]->getValue();
                        $pointsEntries[$index] += 1;
                    }
                }
            }
        }

        //average out the points of a category with the number of ratings
        for ($i = 0; $i < sizeof($points); $i++) {
            if ($pointsEntries[$i] > 0) {
                $points[$i] = $points[$i] / $pointsEntries[$i];
            }
        }
        return $points;
    }

    /**
     * Return whether a meal fits the given settings.
     * @param Meal     $meal The meal to check
     * @param Settings $settings The settings that have to be upheld
     * @param boolean    $usePriceRange Whether the price range setting of the user should be used or not
     * @param City    $location The city for this suggestion
     * @return bool true if it fits otherwise false
     */
    private function fitsSettings(Meal $meal, Settings $settings, $usePriceRange, $location)
    {
        //check price range
        if ($usePriceRange) {
            $priceRange = $settings->getPriceRange();
            if (!is_null($priceRange)) {
                $priceMin = $priceRange->getMin();
                $priceMax = $priceRange->getMax();
                $price = $meal->getPrice();
                if ($priceMax < $price || $priceMin > $price) {
                    return false;
                }
            }
        }


        //check if meal is in the same city
        $mealCity = $meal->getRestaurant()->getAddress()->getCity();
        if (is_null($location)) {
            if (!is_null($settings->getLocation())) {
                $userCity = $settings->getLocation()->getCity();
                if (!is_null($userCity)) {
                    if (strcasecmp($userCity, $mealCity) != 0) {
                        return false;
                    }
                }
            }
        } else {
            if (strcasecmp($location, $mealCity) != 0) {
                return false;
            }
        }
        return true;
    }
}
