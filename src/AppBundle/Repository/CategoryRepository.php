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

use AppBundle\Entity\Meal as Meal;
use AppBundle\Entity\Category as Category;

/**
 * The repository handling the saving, deleting and fetching of categories
 *
 * @package AppBundle\Repository
 */
class CategoryRepository extends BaseRepository
{
    /**
     * Automaticly adds categories to a meal and saves it into the database afterwards.
     * @param Meal $meal The meal which will be categorized
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function categorizeAndSave(Meal $meal)
    {
        $mealCategories = $this->categorize($meal);

        foreach ($mealCategories as $category) {
            $meal->addCategory($category);
        }

        $this->getEntityManager()->getRepository(Meal::class)->save($meal);
    }

    public function categorize($meal)
    {
        $mealCategories = [];
        if (!is_null($meal)) {
            $em = $this->getEntityManager();
            $mealRepo = $em->getRepository(Meal::class);

            //check if a meal with the same name already exists
            $tmp = $mealRepo->getDifferentByName($meal);
            if (!is_null($tmp)) {
                $mealCategories = $tmp->getCategories()->toArray();
            } else {
                $categories = $this->getAll();
                foreach ($categories as $category) {
                    $match = $this->match($meal->getName(), $category);
                    if ($match) {
                        $mealCategories[] = $category;
                    }
                }
            }
        }

        return $mealCategories;
    }

    /**
     * Return whether a name of a meal matches the search terms of a catergory.
     * @param string   $name The name of a meal
     * @param Category $category The category to match
     * @return bool True if it matches, otherwise false
     */
    private function match(string $name, Category $category)
    {
        $searchTerms = $category->getSearchTerms();
        foreach ($searchTerms as $searchTerm) {
            if (preg_match("/$searchTerm/i", $name) == 1) {
                return true;
            }
        }
        return false;
    }
}
