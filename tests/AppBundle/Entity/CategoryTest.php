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

/**
 * Created by PhpStorm.
 * User: ladmin
 * Date: 27.08.18
 * Time: 13:59
 */

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Category;
use AppBundle\Entity\Meal;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{

    public function testGetId()
    {
        $c = new Category();
        $c->setId(48);
        $this->assertEquals(48, $c->getId());
    }

    public function testGetSearchTerms()
    {
        $c = new Category();
        $c->setSearchTerms(["hi", "test"]);
        $this->assertEquals(["hi", "test"], $c->getSearchTerms());
    }

    public function testAddSearchTerm()
    {
        $c = new Category();
        $c->addSearchTerm("search");
        $this->assertEquals(["search"], $c->getSearchTerms());
    }

    public function testGetMeals()
    {
        $c = new Category();
        $array = [new Meal()];
        $c->setMeals($array);
        $this->assertEquals($array, $c->getMeals());
    }

    public function testAddMeal() {
        $c = new Category();
        $m = new Meal();
        $c->addMeal($m);
        $this->assertEquals([$m], $c->getMeals());
    }

    public function testGetName()
    {
        $c = new Category();
        $c->setName("Testcat");
        $this->assertEquals("Testcat", $c->getName());
        $this->assertEquals("Testcat", $c->__toString());
    }
}
