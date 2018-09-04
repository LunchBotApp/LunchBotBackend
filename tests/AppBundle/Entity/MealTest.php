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


use AppBundle\Entity\Category;
use AppBundle\Entity\Meal;
use AppBundle\Entity\Restaurant;
use PHPUnit\Framework\TestCase;

class MealTest extends TestCase
{
    public function test()
    {
        $date       = new \DateTime();
        $restaurant = new Restaurant();
        $restaurant->setName("1");
        $meal = new Meal();
        $cat  = new Category();

        $meal->setId(1);
        $meal->addCategory($cat);
        $meal->setName("1");
        $meal->setRestaurant($restaurant);
        $meal->setDate($date);
        $meal->setPrice(1.1);
        $meal->setAddition("1");
        $meal->setPlace("1");
        $meal->setCategories([$cat]);

        $this->assertEquals("1_1", $meal);
        $this->assertEquals(1, $meal->getId());
        $this->assertEquals("1", $meal->getName());
        $this->assertEquals($restaurant, $meal->getRestaurant());
        $this->assertEquals($date, $meal->getDate());
        $this->assertEquals(1.1, $meal->getPrice());
        $this->assertEquals("1", $meal->getPlace());
        $this->assertEquals("1", $meal->getAddition());
        $this->assertEquals([$cat], $meal->getCategories());
    }
}