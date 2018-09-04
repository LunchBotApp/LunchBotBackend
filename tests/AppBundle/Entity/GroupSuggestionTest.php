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

namespace Tests\AppBundle\Entity;


use AppBundle\Entity\GroupSuggestion;
use AppBundle\Entity\Meal;
use AppBundle\Entity\MealChoice;
use AppBundle\Entity\Rating;
use AppBundle\Entity\Restaurant;
use AppBundle\Entity\Settings;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class GroupSuggestionTest extends TestCase
{
    public function test()
    {
        $user       = new User();
        $restaurant = new Restaurant();
        $rating     = new Rating();
        $sett       = new Settings();
        $date       = new \DateTime();
        $meal       = new MealChoice();
        $m          = new Meal();
        $sugg       = new GroupSuggestion();
        $sugg->setId(1);
        $sugg->addUser($user);
        $sugg->addChosenMeal($m, $user);
        $sugg->setUsers([$user]);
        $sugg->setRestaurant($restaurant);
        $sugg->setSettings($sett);
        $sugg->setTimestamp($date);
        $sugg->setSuggestions([$sugg]);
        $sugg->setMealChoices([$meal]);
        $sugg->setRatings([$rating]);


        $this->assertEquals(1, $sugg->getId());
        $this->assertEquals($sett, $sugg->getSettings());
        $this->assertEquals([$user], $sugg->getUsers());
        $this->assertEquals([$sugg], $sugg->getSuggestions());
        $this->assertEquals($restaurant, $sugg->getRestaurant());
        $this->assertEquals($date, $sugg->getTimestamp());
        $this->assertEquals([$meal], $sugg->getMealChoices());
        $this->assertEquals([$rating], $sugg->getRatings());
    }

}