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
 * Time: 14:03
 */

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\City;
use AppBundle\Entity\Country;
use AppBundle\Entity\Restaurant;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\CacheItem;

class CityTest extends TestCase
{

    public function testGetName()
    {
        $c = new City();
        $c->setName("Stadt");
        $this->assertEquals("Stadt", $c->getName());
    }

    public function testGetCountry()
    {
        $co = new Country();
        $c = new City();
        $c->setCountry($co);
        $this->assertEquals($co, $c->getCountry());
    }

    public function testGetId()
    {
        $c = new City();
        $c->setId(78);
        $this->assertEquals(78, $c->getId());
    }

    public function testGetRestaurants()
    {
        $r = [new Restaurant()];
        $c = new City();
        $c->setRestaurants($r);
        $this->assertEquals($r, $c->getRestaurants());
    }
}
