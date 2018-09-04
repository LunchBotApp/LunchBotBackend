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

namespace AppBundle\Entity;

use AppBundle\Entity\MealChoice as MealChoice;
use AppBundle\Entity\Suggestion as Suggestion;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A suggestion for a multiple users
 *
 * @ORM\Table(name="group_suggestions")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SuggestionRepository")
 * @Serializer\ExclusionPolicy("none")
 */
class GroupSuggestion extends Suggestion
{

    /**
     * @var array The users of a group suggestion.
     *
     * @Assert\NotBlank()
     * @ORM\ManyToMany(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Serializer\Groups({"suggestion"})
     */
    private $users;

    /**
     *
     * @var Restaurant The restaurant that was chosen.
     *
     * @ORM\ManyToOne(targetEntity="Restaurant")
     * @ORM\JoinColumn(name="ChosenRestaurant_id", referencedColumnName="id")
     * @Serializer\Groups({"suggestion"})
     */
    private $restaurant;


    /**
     *
     * @var array The meals that were suggested.
     *
     * @ORM\ManyToMany(targetEntity="Meal")
     * @ORM\JoinColumn(name="meal_id", referencedColumnName="id")
     * @Serializer\Groups({"suggestion"})
     */
    private $suggestions;

    /**
     * Adds an user to this group suggestion
     *
     * @param User|array $user The users which will be added
     */
    public function addUser($user)
    {
        $this->users[] = $user;
    }

    /**
     * Adds which meal a user has chosen.
     *
     * @param Meal $meal The meal which the user has chosen
     * @param User $user The user who chose the meal
     */
    public function addChosenMeal(Meal $meal, User $user)
    {
        $choice = new MealChoice();
        $choice->setUser($user);
        $choice->setMeal($meal);
        $this->mealChoices[] = $choice;
    }

    /**
     * Get the users of a group suggestion.
     *
     * @return array The users of a group suggestion.
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set the users of a group suggestion.
     *
     * @param array $users The users of a group suggestion.
     */
    public function setUsers(array $users)
    {
        $this->users = $users;
    }

    /**
     * Get the restaurant that was chosen.
     *
     * @return Restaurant The restaurant that was chosen.
     */
    public function getRestaurant()
    {
        return $this->restaurant;
    }

    /**
     * Set the restaurant that was chosen.
     *
     * @param Restaurant $restaurant The restaurant that was chosen.
     */
    public function setRestaurant(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
    }

    /**
     * @return array
     */
    public function getSuggestions()
    {
        return $this->suggestions;
    }

    /**
     * @param array $suggestions
     * @return GroupSuggestion
     */
    public function setSuggestions($suggestions)
    {
        $this->suggestions = $suggestions;

        return $this;
    }
}
