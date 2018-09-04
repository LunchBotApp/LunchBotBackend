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
use AppBundle\Entity\GroupSuggestion as GroupSuggestion;
use AppBundle\Entity\Meal as Meal;
use AppBundle\Entity\Settings as Settings;
use AppBundle\Entity\SoloSuggestion as SoloSuggestion;
use AppBundle\Entity\Suggestion as Suggestion;
use AppBundle\Entity\User as User;
//TODO uncomment this part if provider is set up for Restaurants
//use AppBundle\Entity\Restaurant;

/**
 * The repository handling the saving, deleting and fetching of Suggestions
 *
 * @package AppBundle\Repository
 */
class SuggestionRepository extends BaseRepository
{

    /**
     * Calculates the 3 best meal/restaurant for the given suggestion object.
     *
     * @param Suggestion $suggestion The suggestion object with all neccecary information
     * @return array An array of meals if $suggestion is instance of SoloSuggestion.
     * Otherwise an array of restaurants.
     */
    public function getBestMatch(Suggestion $suggestion)
    {
        $em         = $this->getEntityManager();
        $todayMeals = $em->getRepository(Meal::class)->getAllTodayMeals();
        //if there are temporary settings for this suggestion ignore the individual settings of user
        $usePriceRange = true;
        $location      = null;
        if (!is_null($suggestion->getSettings())) {
            $todayMeals    = $this->applySettings($todayMeals, $suggestion->getSettings());
            $usePriceRange = is_null($suggestion->getSettings()->getPriceRange());
            if (!is_null(($suggestion->getSettings()->getLocation()))) {
                $location = $suggestion->getSettings()->getLocation()->getCity();
            }
        }

        if ($suggestion instanceof SoloSuggestion) {
            return $this->getBestSoloMatch($todayMeals, $suggestion, $usePriceRange, $location);
        } else {
            return $this->getBestGroupMatch($todayMeals, $suggestion, $usePriceRange, $location);
        }
    }

    /**
     * Removes all meals from an array which contradict the settings.
     *
     * @param array    $meals The array meals where the settings will be applied to
     * @param Settings $settings The settings that will be applied
     * @return array A filtered meal array
     */
    private function applySettings(array $meals, Settings $settings)
    {
        //Check the price range
        $priceRangeRequired = $settings->getPriceRange();
        if (!is_null($priceRangeRequired)) {
            $priceMinRequired = $priceRangeRequired->getMin();
            $priceMaxRequired = $priceRangeRequired->getMax();
            $numberOfMeals    = sizeof($meals);
            for ($i = 0; $i < $numberOfMeals; $i++) {
                $price = $meals[$i]->getPrice();
                //check minimum price
                if ($price < $priceMinRequired) {
                    unset($meals[$i]);
                    //check maximum price
                } elseif ($price > $priceMaxRequired) {
                    unset($meals[$i]);
                }
            }
        }
        //reassign keys of array
        $meals = array_values($meals);

        //check if meal is in the same city as user
        if (!is_null($settings->getLocation())) {
            $userCity = $settings->getLocation()->getCity();
            if (!is_null($userCity)) {
                $numberOfMeals = sizeof($meals);
                for ($i = 0; $i < $numberOfMeals; $i++) {
                    $mealCity = $meals[$i]->getRestaurant()->getAddress()->getCity();
                    if (strcasecmp($userCity, $mealCity) != 0) {
                        unset($meals[$i]);
                    }
                }
            }
        }

        //reassign keys of array and return
        return array_values($meals);
    }

    /**
     * Returns the 3 best restaurants for given group suggestion.
     * HOTFIX change to 3 best meals.
     *
     * @param array      $todayMeals All meals that are available today
     * @param Suggestion $suggestion The suggestion with all neccecary information
     * @param boolean    $usePriceRange Whether the price range setting of the users should be used or not
     * @param City|null  $location The city for this suggestion
     * @return array An array with 3 restaurants
     */
    private function getBestGroupMatch(array $todayMeals, Suggestion $suggestion, $usePriceRange, $location)
    {
        $em              = $this->getEntityManager();
        $todayMealNumber = sizeof($todayMeals);
        $userRepo        = $em->getRepository(User::class);
        $mealPoints      = array_fill(0, $todayMealNumber, 0);

        $users = $suggestion->getUsers();
        //if no global location is set, then use location of requesting user (here first user)
        if (is_null($location)
            &&!is_null($users[0]->getSettings())
            && !is_null($users[0]->getSettings()->getLocation())) {
            $location = $users[0]->getSettings()->getLocation()->getCity();
        }
        //get all points for each meal from each user
        for ($i = 0; $i < sizeof($users); $i++) {
            $tmp = $userRepo->getAllMatchPoints($todayMeals, $users[$i], $usePriceRange, $location);
            for ($j = 0; $j < $todayMealNumber; $j++) {
                $mealPoints[$j] += $tmp[$j];
            }
        }

        //TODO uncomment this part if provider is set up for Restaurants and delete part below which returns meals
        /*$restaurants      = $em->getRepository(Restaurant::class)->getAll();
        $restaurantNumber = sizeof($restaurants);
        $restaurantPoints = array_fill(0, $restaurantNumber, 0);
        //count how many point ratings exist for a restaurant
        $restaurantPointsEntries = array_fill(0, $restaurantNumber, 0);

        //add up the total amount of points for each restaurant
        for ($i = 0; $i < $todayMealNumber; $i++) {
            $index                           = array_search($todayMeals[$i]->getRestaurant(), $restaurants);
            $restaurantPoints[$index]        += $mealPoints[$i];
            $restaurantPointsEntries[$index] += 1;
        }

        //average the points of each restaurant with their number of meals
        for ($i = 0; $i < $restaurantNumber; $i++) {
            if ($restaurantPointsEntries[$i] > 0) {
                $restaurantPoints[$i] = $restaurantPoints[$i] / $restaurantPointsEntries[$i];
            }
        }

        //get the 3 best restaurants
        arsort($restaurantPoints);
        $highestPointsKey = array_slice(array_keys($restaurantPoints), 0, 3);
        $bestRestaurant   = [];
        for ($i = 0; $i < sizeof($highestPointsKey); $i++) {
            $bestRestaurant[$i] = $restaurants[$highestPointsKey[$i]];
        }

        return $bestRestaurant;*/

        //get the 3 best meals
        arsort($mealPoints);
        $highestPointsKey = array_slice(array_keys($mealPoints), 0, 3);
        $bestMeals        = [];
        for ($i = 0; $i < sizeof($highestPointsKey); $i++) {
            $bestMeals[$i] = $todayMeals[$highestPointsKey[$i]];
        }

        return $bestMeals;
    }

    /**
     * Returns the 3 best meals for given solo suggestion.
     *
     * @param array          $todayMeals All meals that are available today
     * @param SoloSuggestion $suggestion The suggestion with all necesscary information
     * @param boolean        $usePriceRange Whether the price range setting of the user should be used or not
     * @param City|null      $location The city for this suggestion
     * @return array An array with 3 meals
     */
    private function getBestSoloMatch($todayMeals, SoloSuggestion $suggestion, $usePriceRange, $location)
    {
        $userRepo = $this->getEntityManager()->getRepository(User::class);

        //get the full information about a user from database
        //$user = $userRepo->getByUserId($suggestion->getUser()->getUserId());
        $user = $suggestion->getUser();

        return $userRepo->getBestMatch($todayMeals, $user, $usePriceRange, $location);
    }
}
